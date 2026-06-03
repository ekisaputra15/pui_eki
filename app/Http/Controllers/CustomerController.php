<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;

class CustomerController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function noTable()
    {
        return view('customer.notable');
    }

    public function scan($table_id)
    {
        $table = Table::where('name', $table_id)->first();
        if (!$table) {
            return redirect()->route('customer.notable')->withErrors(['table' => 'Meja tidak ditemukan.']);
        }

        $currentTable = session('table_id');

        // --- TABLE ISOLATION ---
        // Jika scan meja BERBEDA dari sebelumnya, bersihkan semua sesi meja lama
        if ($currentTable && $currentTable !== $table->name) {
            session()->forget(['cart', 'latest_order_id', 'latest_order_tracking_id']);
        }

        // Set sesi meja baru
        session(['table_id' => $table->name]);

        // Jika scan meja YANG SAMA dan ada pesanan aktif, langsung ke halaman status
        $existingOrderId = session('latest_order_id');
        if ($existingOrderId) {
            $existingOrder = Order::find($existingOrderId);
            if ($existingOrder
                && $existingOrder->table_id === $table->name
                && !in_array($existingOrder->status, ['selesai', 'dibatalkan'])) {
                return redirect()->route('customer.status');
            }
        }

        return redirect()->route('customer.menu');
    }

    public function updateCart(Request $request)
    {
        $itemId = $request->input('item_id');
        $action = $request->input('action');
        
        $cart = session('cart', []);

        if ($action === 'add' && $itemId) {
            $product = Product::find($itemId);
            if($product) {
                if (isset($cart[$itemId])) {
                    $cart[$itemId]['quantity'] += 1;
                } else {
                    $cart[$itemId] = [
                        'id' => $itemId,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => 1
                    ];
                }
            }
        } elseif ($action === 'remove' && $itemId) {
            if (isset($cart[$itemId])) {
                $cart[$itemId]['quantity'] -= 1;
                if ($cart[$itemId]['quantity'] <= 0) {
                    unset($cart[$itemId]);
                }
            }
        }

        session(['cart' => $cart]);
        return redirect()->back();
    }

    public function menu()
    {
        if (!session()->has('table_id')) {
            return redirect()->route('customer.notable');
        }

        $products = Product::all();
        $categories = $products->groupBy('category');

        return view('customer.menu', compact('categories', 'products'));
    }

    public function cart()
    {
        if (!session()->has('table_id')) {
            return redirect()->route('customer.notable');
        }
        
        $cart = session('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('customer.cart', compact('cart', 'total'));
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->withErrors(['cart' => 'Keranjang kosong.']);
        }

        $table_id = session('table_id');
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'order_id' => 'ORD-' . time() . rand(100, 999),
            'table_id' => $table_id,
            'total' => $total,
            'status' => 'pending',
            'notes' => $request->input('notes')
        ]);

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);
        }

        session()->forget('cart');
        session(['latest_order_id' => $order->id]);
        session(['latest_order_tracking_id' => $order->order_id]);
        
        // --- REAL-TIME NOTIFICATION ---
        $firebase = new \App\Services\FirebaseService();
        $firebase->notifyRole('kasir', "Pesanan baru dari Meja {$table_id}", 'success');
        $firebase->notifyOrderStatus($order->order_id, 'pending');

        return redirect()->route('customer.status');
    }

    public function status()
    {
        $order_id = session('latest_order_id');
        $table_id = session('table_id');

        if (!$order_id) {
            return redirect()->route('customer.menu');
        }

        $order = Order::with('items')->find($order_id);
        if (!$order) {
            return redirect()->route('customer.menu');
        }

        // Riwayat pesanan KHUSUS meja ini saja (bukan meja lain)
        $tableHistory = Order::with('items')
            ->where('table_id', $order->table_id)       // filter by THIS table only
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->where('id', '!=', $order->id)             // exclude current order
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $currentStatus = $order->status;
        return view('customer.status', compact('order', 'currentStatus', 'tableHistory'));
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::where('order_id', $request->order_id)->firstOrFail();
        
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $order->update(['status' => 'dibatalkan']);
        
        // --- REAL-TIME NOTIFICATION ---
        $firebase = new \App\Services\FirebaseService();
        $firebase->notifyRole('kasir', "Pesanan Meja {$order->table_id} dibatalkan oleh pelanggan.", 'warning');
        $firebase->notifyOrderStatus($order->order_id, 'dibatalkan');

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
