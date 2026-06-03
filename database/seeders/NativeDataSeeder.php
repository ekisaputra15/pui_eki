<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\File;

class NativeDataSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('../core/data.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("File data.json tidak ditemukan di $jsonPath");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        // 1. Import Users
        $this->command->info('Importing Users...');
        foreach ($data['users'] ?? [] as $u) {
            User::updateOrCreate(
                ['username' => $u['username']],
                [
                    'name' => $u['name'] ?? ucfirst($u['username']),
                    'email' => $u['email'] ?? (($u['username'] ?? $u['role']) . '@emenu.com'),
                    'password' => Hash::make($u['password'] ?? 'password'),
                    'role' => $u['role']
                ]
            );
        }

        // 2. Import Tables
        $this->command->info('Importing Tables...');
        foreach ($data['tables'] ?? [] as $t) {
            Table::updateOrCreate(
                ['name' => (string)$t['id']],
                ['status' => ($t['status'] === 'clear' ? 'available' : 'dirty')]
            );
        }

        // 3. Import Products
        $this->command->info('Importing Menu/Products...');
        foreach ($data['menu'] ?? [] as $m) {
            Product::updateOrCreate(
                ['name' => $m['name']],
                [
                    'price' => $m['price'],
                    'category' => $m['category'],
                    'image' => $m['image'] ? str_replace('/assets/', '/', $m['image']) : null
                ]
            );
        }

        $this->command->info('Data Import Selesai!');
    }
}
