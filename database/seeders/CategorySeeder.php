<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hardware', 'icon' => '🖥️', 'description' => 'PC, printer, scanner, dll'],
            ['name' => 'Software', 'icon' => '💿', 'description' => 'Aplikasi, OS, driver'],
            ['name' => 'Network', 'icon' => '🌐', 'description' => 'Internet, LAN, WiFi'],
            ['name' => 'Aplikasi Core Banking', 'icon' => '🏦', 'description' => 'Masalah aplikasi inti'],
            ['name' => 'ATM / EDC', 'icon' => '💳', 'description' => 'Mesin ATM dan EDC'],
            ['name' => 'Email', 'icon' => '📧', 'description' => 'Outlook, webmail'],
            ['name' => 'Akses & User', 'icon' => '🔐', 'description' => 'Reset password, akses aplikasi'],
            ['name' => 'Lainnya', 'icon' => '❓', 'description' => 'Kategori lain'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $this->command->info('✅ Kategori tiket berhasil dibuat!');
    }
}