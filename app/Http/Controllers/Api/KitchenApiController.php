<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Table;
use App\Services\FirebaseService;

class KitchenApiController extends Controller
{
    public function index()
    {
        // Get orders that are diproses or dibuat (FIFO)
        $orders = Order::with('items')
            ->whereIn('status', ['diproses', 'dibuat'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function action(Request $request, $order_id)
    {
        $request->validate([
            'action' => 'required|in:mark_cooking,complete_order'
        ]);

        $order = Order::where('order_id', $order_id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $firebase = new FirebaseService();

        if ($request->action === 'mark_cooking' && $order->status === 'diproses') {
            $order->update(['status' => 'dibuat']);
            $firebase->notifyOrderStatus($order->order_id, 'dibuat');
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan sedang dimasak',
                'data' => $order
            ]);
            
        } elseif ($request->action === 'complete_order' && $order->status === 'dibuat') {
            $order->update(['status' => 'selesai']);
            $firebase->notifyOrderStatus($order->order_id, 'selesai');
            
            // --- REAL-TIME NOTIFICATION TO WAITRESS ---
            $firebase->notifyRole('waitress', "Pesanan Meja {$order->table_id} siap disajikan!", 'success');

            $table = Table::where('name', $order->table_id)->first();
            if ($table) {
                $table->update(['status' => 'dirty']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan selesai dan siap disajikan',
                'data' => $order
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid untuk status pesanan saat ini'
        ], 400);
    }
}
