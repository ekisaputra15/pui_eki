<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;

class EmenugoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        User::create(['username' => 'admin', 'password' => Hash::make('password'), 'role' => 'pengelola']);
        User::create(['username' => 'kasir', 'password' => Hash::make('password'), 'role' => 'kasir']);
        User::create(['username' => 'dapur', 'password' => Hash::make('password'), 'role' => 'dapur']);
        User::create(['username' => 'waitress', 'password' => Hash::make('password'), 'role' => 'waitress']);

        // 2. Seed Tables
        $tables = array (
  0 => 
  array (
    'id' => '1',
    'status' => 'dirty',
    'updated_at' => '2026-04-13 16:09:33',
  ),
  1 => 
  array (
    'id' => '2',
    'status' => 'dirty',
  ),
  2 => 
  array (
    'id' => '3',
    'status' => 'dirty',
  ),
  3 => 
  array (
    'id' => '4',
    'status' => 'dirty',
  ),
  4 => 
  array (
    'id' => 'vip',
    'status' => 'dirty',
    'updated_at' => '2026-04-13 22:21:12',
  ),
  5 => 
  array (
    'id' => 'Private Room',
    'status' => 'clear',
  ),
  6 => 
  array (
    'id' => 'Meja 5',
    'status' => 'clear',
    'updated_at' => '2026-04-27 11:44:35',
  ),
);
        foreach ($tables as $t) {
            Table::create([
                'name' => $t['id'],
                'status' => $t['status'] ?? 'clear',
            ]);
        }

        // 3. Seed Products
        $products = array (
  0 => 
  array (
    'id' => '1',
    'name' => 'Nasi Goreng Spesial',
    'price' => 25000,
    'category' => 'Makanan',
    'image' => '/assets/uploads/products/prod_69dcd3be5bbc25.65525661.jpg',
  ),
  1 => 
  array (
    'id' => '2',
    'name' => 'Mie Goreng Seafood',
    'price' => 30000,
    'category' => 'Makanan',
    'image' => '',
  ),
  2 => 
  array (
    'id' => '3',
    'name' => 'Es Teh Manis',
    'price' => 5000,
    'category' => 'Minuman',
    'image' => '',
  ),
  3 => 
  array (
    'id' => '4',
    'name' => 'Kopi Hitam',
    'price' => 10000,
    'category' => 'Minuman',
    'image' => '',
  ),
  4 => 
  array (
    'id' => '5',
    'name' => 'Americano',
    'price' => 20000,
    'category' => 'Minuman',
    'image' => '/assets/uploads/products/prod_69dcec8b56a060.00868198.jpg',
  ),
);
        foreach ($products as $p) {
            Product::create([
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => $p['price'],
                'category' => $p['category'] ?? 'Lainnya',
                'image' => $p['image'] ?? null,
            ]);
        }

        // 4. Seed Orders
        $orders = array (
  0 => 
  array (
    'order_id' => 'ORD-1776072878',
    'table' => 'Walk-in',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 3,
      ),
    ),
    'total' => 75000,
    'status' => 'selesai',
    'created_at' => '2026-04-13 11:34:38',
  ),
  1 => 
  array (
    'order_id' => 'ORD-1776077094',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 2,
        'name' => 'Mie Goreng Seafood',
        'price' => 30000,
        'quantity' => 1,
      ),
    ),
    'total' => 30000,
    'status' => 'selesai',
    'created_at' => '2026-04-13 12:44:54',
  ),
  2 => 
  array (
    'order_id' => 'ORD-1776078178',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'selesai',
    'created_at' => '2026-04-13 13:02:58',
  ),
  3 => 
  array (
    'order_id' => 'ORD-1776078305',
    'table' => '3',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 2,
      ),
    ),
    'total' => 50000,
    'status' => 'selesai',
    'created_at' => '2026-04-13 13:05:05',
  ),
  4 => 
  array (
    'order_id' => 'ORD-1776078404',
    'table' => '4',
    'items' => 
    array (
      0 => 
      array (
        'id' => 3,
        'name' => 'Es Teh Manis',
        'price' => 5000,
        'quantity' => 1,
      ),
    ),
    'total' => 5000,
    'status' => 'selesai',
    'created_at' => '2026-04-13 13:06:44',
  ),
  5 => 
  array (
    'order_id' => 'ORD-1776078762',
    'table' => 'vip',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'selesai',
    'created_at' => '2026-04-13 13:12:42',
  ),
  6 => 
  array (
    'order_id' => 'ORD-1776080298',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'selesai',
    'notes' => 'sambel nya yang banyak',
    'created_at' => '2026-04-13 13:38:18',
  ),
  7 => 
  array (
    'order_id' => 'ORD-1776081469',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'dibatalkan',
    'notes' => '',
    'created_at' => '2026-04-13 13:57:49',
  ),
  8 => 
  array (
    'order_id' => 'ORD-1776084141',
    'table' => '2',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'selesai',
    'notes' => 'tambah 1 lagi mas',
    'created_at' => '2026-04-13 14:42:21',
  ),
  9 => 
  array (
    'order_id' => 'ORD-1776086313',
    'table' => 'vip',
    'items' => 
    array (
      0 => 
      array (
        'id' => 5,
        'name' => 'Americano',
        'price' => 20000,
        'quantity' => 3,
      ),
    ),
    'total' => 60000,
    'status' => 'selesai',
    'notes' => '',
    'created_at' => '2026-04-13 15:18:33',
  ),
  10 => 
  array (
    'order_id' => 'ORD-1776086674',
    'table' => '2',
    'items' => 
    array (
      0 => 
      array (
        'id' => 4,
        'name' => 'Kopi Hitam',
        'price' => 10000,
        'quantity' => 1,
      ),
    ),
    'total' => 10000,
    'status' => 'diproses',
    'notes' => 'jkk',
    'created_at' => '2026-04-13 15:24:34',
  ),
  11 => 
  array (
    'order_id' => 'ORD-1776086678',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 15:24:38',
  ),
  12 => 
  array (
    'order_id' => 'ORD-1776088311',
    'table' => 'vip',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 2,
      ),
    ),
    'total' => 50000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 15:51:51',
  ),
  13 => 
  array (
    'order_id' => 'ORD-1776088432',
    'table' => 'vip',
    'items' => 
    array (
      0 => 
      array (
        'id' => 2,
        'name' => 'Mie Goreng Seafood',
        'price' => 30000,
        'quantity' => 1,
      ),
    ),
    'total' => 30000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 15:53:52',
  ),
  14 => 
  array (
    'order_id' => 'ORD-1776088479',
    'table' => 'vip',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 15:54:39',
  ),
  15 => 
  array (
    'order_id' => 'ORD-1776088751',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 3,
        'name' => 'Es Teh Manis',
        'price' => 5000,
        'quantity' => 1,
      ),
    ),
    'total' => 5000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 15:59:11',
  ),
  16 => 
  array (
    'order_id' => 'ORD-1776088787',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 15:59:47',
  ),
  17 => 
  array (
    'order_id' => 'ORD-1776088968',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 4,
        'name' => 'Kopi Hitam',
        'price' => 10000,
        'quantity' => 1,
      ),
    ),
    'total' => 10000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 16:02:48',
  ),
  18 => 
  array (
    'order_id' => 'ORD-1776088997',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'selesai',
    'notes' => '',
    'created_at' => '2026-04-13 16:03:17',
  ),
  19 => 
  array (
    'order_id' => 'ORD-1776089031',
    'table' => '1',
    'items' => 
    array (
      0 => 
      array (
        'id' => 2,
        'name' => 'Mie Goreng Seafood',
        'price' => 30000,
        'quantity' => 1,
      ),
    ),
    'total' => 30000,
    'status' => 'selesai',
    'notes' => '',
    'created_at' => '2026-04-13 16:03:51',
  ),
  20 => 
  array (
    'order_id' => 'ORD-1776092557',
    'table' => '2',
    'items' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Nasi Goreng Spesial',
        'price' => 25000,
        'quantity' => 1,
      ),
    ),
    'total' => 25000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 17:02:37',
  ),
  21 => 
  array (
    'order_id' => 'ORD-1776092578',
    'table' => '2',
    'items' => 
    array (
      0 => 
      array (
        'id' => 2,
        'name' => 'Mie Goreng Seafood',
        'price' => 30000,
        'quantity' => 1,
      ),
    ),
    'total' => 30000,
    'status' => 'diproses',
    'notes' => '',
    'created_at' => '2026-04-13 17:02:58',
  ),
  22 => 
  array (
    'order_id' => 'ORD-1776093123',
    'table' => '2',
    'items' => 
    array (
      0 => 
      array (
        'id' => 4,
        'name' => 'Kopi Hitam',
        'price' => 10000,
        'quantity' => 1,
      ),
    ),
    'total' => 10000,
    'status' => 'pending',
    'notes' => '',
    'created_at' => '2026-04-13 22:12:03',
  ),
);
        foreach ($orders as $o) {
            $order = Order::create([
                'order_id' => $o['order_id'],
                'table_id' => $o['table'] ?? 'Walk-in',
                'total' => $o['total'] ?? 0,
                'status' => $o['status'] ?? 'pending',
                'notes' => $o['notes'] ?? null,
                'created_at' => $o['created_at'] ?? now(),
                'updated_at' => $o['created_at'] ?? now(),
            ]);

            if (isset($o['items']) && is_array($o['items'])) {
                foreach ($o['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }
    }
}
