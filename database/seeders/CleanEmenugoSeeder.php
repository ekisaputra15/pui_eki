<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Support\Facades\Hash;

class CleanEmenugoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Essential Users
        User::create(['name' => 'Administrator', 'username' => 'admin', 'password' => Hash::make('12345'), 'role' => 'pengelola']);
        User::create(['name' => 'Kasir Utama', 'username' => 'kasir', 'password' => Hash::make('12345'), 'role' => 'kasir']);
        User::create(['name' => 'Chef Dapur', 'username' => 'dapur', 'password' => Hash::make('12345'), 'role' => 'dapur']);
        User::create(['name' => 'Waitress 1', 'username' => 'waitress', 'password' => Hash::make('12345'), 'role' => 'waitress']);

        // 2. Seed Default Tables (Clean Status)
        $tableNames = ['1', '2', '3', '4', '5', 'VIP 1', 'VIP 2'];
        foreach ($tableNames as $name) {
            Table::create([
                'name' => $name,
                'status' => 'clear'
            ]);
        }

        // 3. Seed Sample Products
        $products = [
            [
                'name'        => 'Nasi Goreng Spesial',
                'price'       => 25000,
                'category'    => 'Makanan',
                'description' => 'Nasi goreng dengan telur, ayam suwir, dan kerupuk renyah. Cocok untuk santap siang.',
                'image'       => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=800&q=85&fit=crop',
            ],
            [
                'name'        => 'Mie Goreng Seafood',
                'price'       => 30000,
                'category'    => 'Makanan',
                'description' => 'Mie goreng dengan udang dan cumi segar, bumbu rempah khas restoran.',
                'image'       => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800&q=85&fit=crop',
            ],
            [
                'name'        => 'Es Teh Manis',
                'price'       => 5000,
                'category'    => 'Minuman',
                'description' => 'Teh segar manis dengan es batu pilihan. Menyegarkan di hari panas.',
                'image'       => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=800&q=85&fit=crop',
            ],
            [
                'name'        => 'Kopi Hitam',
                'price'       => 10000,
                'category'    => 'Minuman',
                'description' => 'Kopi hitam pekat tanpa gula, seduh langsung dari biji pilihan.',
                'image'       => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800&q=85&fit=crop',
            ],
            [
                'name'        => 'Americano',
                'price'       => 20000,
                'category'    => 'Minuman',
                'description' => 'Espresso shot dengan air panas, memberikan cita rasa kopi yang bersih dan tegas.',
                'image'       => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?w=800&q=85&fit=crop',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
