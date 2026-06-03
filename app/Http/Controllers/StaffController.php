<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function cashierIndex()
    {
        $orders = \App\Models\Order::with('items')->orderBy('created_at', 'desc')->get();
        return view('staff.cashier.index', compact('orders'));
    }

    public function cashierTables()
    {
        $tables = \App\Models\Table::all();
        return view('staff.cashier.tables', compact('tables'));
    }

    public function cashierTableStore(Request $request)
    {
        $request->validate([
            'table_label' => 'required|unique:tables,name',
        ]);

        \App\Models\Table::create([
            'name' => $request->table_label,
            'status' => 'clear'
        ]);

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function tableDetail($id)
    {
        $table = \App\Models\Table::where('name', $id)->firstOrFail();
        $activeOrders = \App\Models\Order::with('items')
            ->where('table_id', $id)
            ->whereIn('status', ['pending', 'diproses', 'dibuat'])
            ->get();
            
        $totalBill = $activeOrders->sum('total');
        
        return view('staff.cashier.table_detail', compact('table', 'activeOrders', 'totalBill'));
    }

    public function kitchenIndex()
    {
        $orders = \App\Models\Order::with('items')
            ->whereIn('status', ['diproses', 'dibuat'])
            ->orderBy('created_at', 'asc')->get();
            
        $diprosesCount = $orders->where('status', 'diproses')->count();
        $dibuatCount = $orders->where('status', 'dibuat')->count();
        $totalAntrian = $orders->count();

        return view('staff.kitchen.index', compact('orders', 'diprosesCount', 'dibuatCount', 'totalAntrian'));
    }

    public function waitressIndex()
    {
        $tables = \App\Models\Table::all();
        // Get history (Waitress Scan Logs)
        $history = \App\Models\WaitressHistory::orderBy('created_at', 'desc')->take(10)->get();
        return view('staff.waitress.index', compact('tables', 'history'));
    }

    public function waitressScan()
    {
        return view('staff.waitress.scan');
    }

    public function clearTable(Request $request)
    {
        $table = \App\Models\Table::where('name', $request->table_id)->first();
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Meja tidak ditemukan.']);
        }

        if ($table->status !== 'dirty') {
            return response()->json(['success' => false, 'message' => 'Meja tidak sedang kotor.']);
        }

        $table->update(['status' => 'clear']);

        // Log to history
        \App\Models\WaitressHistory::create([
            'table_id' => $table->name,
            'waitress_name' => auth()->user()->name ?? 'System'
        ]);

        return response()->json(['success' => true, 'message' => "Meja {$table->name} berhasil dibersihkan!"]);
    }

    public function waitressHistory(Request $request)
    {
        $tableFilter = $request->query('table');
        $query = \App\Models\WaitressHistory::orderBy('created_at', 'desc');
        
        if ($tableFilter) {
            $query->where('table_id', $tableFilter);
        }

        $history = $query->paginate(20);
        $totalLogs = \App\Models\WaitressHistory::count();
        $filteredLogs = $history->total();

        return view('staff.waitress.history', compact('history', 'tableFilter', 'totalLogs', 'filteredLogs'));
    }

    public function cashierConfirm(Request $request)
    {
        $order = \App\Models\Order::where('order_id', $request->order_id)->first();
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'diproses']);
            
            // --- REAL-TIME NOTIFICATION ---
            $firebase = new \App\Services\FirebaseService();
            $firebase->notifyRole('dapur', "Pesanan baru dikonfirmasi (Meja {$order->table_id})", 'info');
            $firebase->notifyOrderStatus($order->order_id, 'diproses');
        }
        return redirect()->back();
    }

    public function kitchenAction(Request $request)
    {
        $order = \App\Models\Order::where('order_id', $request->order_id)->first();
        if ($order) {
            $firebase = new \App\Services\FirebaseService();
            if ($request->action === 'mark_cooking' && $order->status === 'diproses') {
                $order->update(['status' => 'dibuat']);
                $firebase->notifyOrderStatus($order->order_id, 'dibuat');
            } elseif ($request->action === 'complete_order' && $order->status === 'dibuat') {
                $order->update(['status' => 'selesai']);
                $firebase->notifyOrderStatus($order->order_id, 'selesai');
                
                // --- REAL-TIME NOTIFICATION TO WAITRESS ---
                $firebase->notifyRole('waitress', "Pesanan Meja {$order->table_id} siap disajikan!", 'success');

                $table = \App\Models\Table::where('name', $order->table_id)->first();
                if($table) {
                    $table->update(['status' => 'dirty']);
                }
            }
        }
        return redirect()->back();
    }
}
