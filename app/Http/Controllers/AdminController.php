<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::with('items')->orderBy('created_at', 'desc')->get();
        $totalRevenue = \App\Models\Order::where('status', 'selesai')->sum('total');
        $selesaiCount = \App\Models\Order::where('status', 'selesai')->count();
        $totalOrders = $orders->count();
        
        return view('admin.index', compact('orders', 'totalRevenue', 'selesaiCount', 'totalOrders'));
    }

    public function orderDetail($id)
    {
        $order = \App\Models\Order::with('items')->where('order_id', $id)->firstOrFail();
        return view('admin.orders.detail', compact('order'));
    }

    // Product Management
    public function productIndex()
    {
        $products = \App\Models\Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = '/uploads/products/' . $imageName;
        }

        \App\Models\Product::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function productUpdate(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = '/uploads/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function productDestroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    // Table Management
    public function tableIndex()
    {
        $tables = \App\Models\Table::all();
        return view('admin.tables.index', compact('tables'));
    }

    public function tableStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tables,name',
        ]);

        \App\Models\Table::create([
            'name' => $request->name,
            'status' => 'clear'
        ]);

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function tableDestroy($id)
    {
        $table = \App\Models\Table::findOrFail($id);
        $table->delete();
        return redirect()->back()->with('success', 'Meja berhasil dihapus.');
    }

    // User (Staff) Management
    public function userIndex()
    {
        $users = \App\Models\User::whereNot('role', 'pengelola')->get();
        return view('admin.users.index', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:kasir,dapur,waitress,pengelola',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Staff berhasil ditambahkan.');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'role' => 'required|in:kasir,dapur,waitress,pengelola',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function userDestroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
