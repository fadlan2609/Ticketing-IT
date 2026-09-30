<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::create([
            'app_name' => 'Amanah Service Desk',
            'company_name' => 'BPRS Amanah Bangsa',
            'company_short_name' => 'BPRS AB',
            'tagline' => 'Amanah, Profesional, Terpercaya',
            'address' => 'Jl. Sudirman No. 123',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40111',
            'phone' => '022-1234567',
            'email' => 'info@bprs-amanah.test',
            'website' => 'https://bprs-amanah.test',
            'timezone' => 'Asia/Jakarta',
            'locale' => 'id',
            'currency' => 'IDR',
        ]);

        $this->command->info('✅ Company Setting seeded!');
    }
}