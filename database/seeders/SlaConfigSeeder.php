<?php

namespace Database\Seeders;

use App\Models\SlaConfig;
use App\Models\WorkingHour;
use Illuminate\Database\Seeder;

class SlaConfigSeeder extends Seeder
{
    public function run(): void
    {
        // SLA per prioritas (dalam menit)
        $configs = [
            ['priority' => 'critical', 'response_minutes' => 15,   'resolution_minutes' => 240],   // 4 jam
            ['priority' => 'high',     'response_minutes' => 30,   'resolution_minutes' => 480],   // 8 jam
            ['priority' => 'medium',   'response_minutes' => 120,  'resolution_minutes' => 1440],  // 24 jam
            ['priority' => 'low',      'response_minutes' => 240,  'resolution_minutes' => 4320],  // 72 jam
        ];
        foreach ($configs as $config) {
            SlaConfig::create($config);
        }

        // Jam kerja: Senin-Jumat 08:00-17:00
        for ($day = 0; $day <= 6; $day++) {
            $isWorking = $day >= 1 && $day <= 5;
            WorkingHour::create([
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'is_working_day' => $isWorking,
            ]);
        }

        $this->command->info('✅ SLA Config & Working Hours berhasil dibuat!');
    }
}