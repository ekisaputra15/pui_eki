<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PengelolaApiController extends Controller
{
    /**
     * Get dashboard stats for Pengelola mobile app.
     */
    public function stats(Request $request)
    {
        $orders = Order::with('items')->orderBy('created_at', 'desc')->get();
        
        $totalRevenue = Order::where('status', 'selesai')->sum('total');
        $selesaiCount = Order::where('status', 'selesai')->count();
        $totalOrders = $orders->count();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'table_id' => $order->table_id,
                'total' => (float) $order->total,
                'status' => $order->status,
                'notes' => $order->notes,
                'created_at' => $order->created_at->toISOString(),
                'updated_at' => $order->updated_at->toISOString(),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => (float) $item->price,
                        'quantity' => $item->quantity,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_orders' => $totalOrders,
                    'selesai_count' => $selesaiCount,
                    'total_revenue' => (float) $totalRevenue,
                ],
                'orders' => $formattedOrders,
            ],
        ]);
    }

    // ==========================================
    // PRODUCT MANAGEMENT
    // ==========================================

    public function products()
    {
        $products = Product::all();
        return response()->json(['success' => true, 'data' => $products]);
    }

    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = '/uploads/products/' . $imageName;
        }

        $product = Product::create($data);
        return response()->json(['success' => true, 'data' => $product, 'message' => 'Produk berhasil ditambahkan']);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = '/uploads/products/' . $imageName;
        }

        $product->update($data);
        return response()->json(['success' => true, 'data' => $product, 'message' => 'Produk berhasil diubah']);
    }

    public function destroyProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus']);
    }

    // ==========================================
    // TABLE MANAGEMENT
    // ==========================================

    public function tables()
    {
        $tables = Table::all();
        return response()->json(['success' => true, 'data' => $tables]);
    }

    public function storeTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:tables,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $table = Table::create([
            'name' => $request->name,
            'status' => 'clear'
        ]);

        return response()->json(['success' => true, 'data' => $table, 'message' => 'Meja berhasil ditambahkan']);
    }

    public function destroyTable($id)
    {
        $table = Table::find($id);
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Meja tidak ditemukan'], 404);
        }
        $table->delete();
        return response()->json(['success' => true, 'message' => 'Meja berhasil dihapus']);
    }

    // ==========================================
    // STAFF MANAGEMENT
    // ==========================================

    public function staff()
    {
        $users = User::whereNot('role', 'pengelola')->get();
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function storeStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:kasir,dapur,waitress',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['success' => true, 'data' => $user, 'message' => 'Staff berhasil ditambahkan']);
    }

    public function updateStaff(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Staff tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required|in:kasir,dapur,waitress',
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->name,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json(['success' => true, 'data' => $user, 'message' => 'Staff berhasil diperbarui']);
    }

    public function destroyStaff($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Staff tidak ditemukan'], 404);
        }
        
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa menghapus akun sendiri'], 400);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'Staff berhasil dihapus']);
    }
}
