<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\FirebaseService;

class KasirApiController extends Controller
{
    public function index(Request $request)
    {
        // By default return all orders, or filter by status if provided
        $query = Order::with('items')->orderBy('created_at', 'desc');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    public function confirm(Request $request, $order_id)
    {
        $order = Order::where('order_id', $order_id)->first();
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dalam status pending'
            ], 400);
        }

        $order->update(['status' => 'diproses']);
        
        // --- REAL-TIME NOTIFICATION ---
        $firebase = new FirebaseService();
        $firebase->notifyRole('dapur', "Pesanan baru dikonfirmasi (Meja {$order->table_id})", 'info');
        $firebase->notifyOrderStatus($order->order_id, 'diproses');

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dikonfirmasi',
            'data' => $order
        ]);
    }

    public function tables()
    {
        $tables = \App\Models\Table::all();
        $tableData = [];

        foreach ($tables as $table) {
            $activeOrders = \App\Models\Order::with('items')
                ->where('table_id', $table->name)
                ->whereIn('status', ['pending', 'diproses', 'dibuat'])
                ->get();
                
            $totalBill = $activeOrders->sum('total');
            
            $tableData[] = [
                'id' => $table->id,
                'name' => $table->name,
                'status' => $table->status,
                'active_orders_count' => $activeOrders->count(),
                'total_bill' => $totalBill
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $tableData
        ]);
    }
}
