<?php

namespace Database\Seeders;

use App\Models\OfficeInfo;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficeInfoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@bprs.test')->first();
        if (!$admin) return;

        $infos = [
            [
                'title' => 'Jam Operasional Kantor',
                'category' => 'hours',
                'icon' => '🕐',
                'is_pinned' => true,
                'summary' => 'Jam layanan kantor pusat dan cabang BPRS Amanah Bangsa.',
                'content' => "Jam Operasional:\n\nSenin - Jumat: 08:00 - 16:00 WIB\nSabtu: 08:00 - 12:00 WIB\nMinggu & Hari Libur: Tutup\n\nLayanan Customer Service:\nSenin - Jumat: 08:00 - 15:30 WIB",
                'metadata' => [
                    ['label' => 'Senin - Jumat', 'value' => '08:00 - 16:00'],
                    ['label' => 'Sabtu', 'value' => '08:00 - 12:00'],
                    ['label' => 'Minggu', 'value' => 'Tutup'],
                ],
            ],
            [
                'title' => 'Kontak Darurat IT Support',
                'category' => 'contact',
                'icon' => '📞',
                'is_pinned' => true,
                'summary' => 'Nomor kontak penting untuk gangguan IT darurat.',
                'content' => "Untuk gangguan IT yang bersifat darurat (critical), silakan hubungi:\n\nHelpdesk: ext. 123\nIT Support On-Call: 0812-3456-7890\nEmail: it.support@bprs.test\n\nJam kerja: 24/7 untuk critical issue",
                'metadata' => [
                    ['label' => 'Helpdesk', 'value' => 'ext. 123'],
                    ['label' => 'On-Call', 'value' => '0812-3456-7890'],
                    ['label' => 'Email', 'value' => 'it.support@bprs.test'],
                ],
            ],
            [
                'title' => 'Kebijakan Penggunaan Aplikasi Core Banking',
                'category' => 'policy',
                'icon' => '📋',
                'is_pinned' => false,
                'summary' => 'Aturan penggunaan aplikasi CBS untuk seluruh karyawan.',
                'content' => "1. Setiap karyawan wajib menggunakan akun pribadi.\n2. Dilarang meminjamkan password ke rekan kerja.\n3. Ganti password setiap 90 hari.\n4. Laporkan segera jika ada aktivitas mencurigakan.\n5. Logout setelah selesai menggunakan aplikasi.\n\nPelanggaran akan dikenakan sanksi sesuai kebijakan HRD.",
            ],
            [
                'title' => 'Selamat Datang di Amanah Service Desk',
                'category' => 'announcement',
                'icon' => '🎉',
                'is_pinned' => false,
                'summary' => 'Sistem ticketing baru untuk mempermudah pelaporan gangguan IT.',
                'content' => "Dengan sistem ini, Anda dapat:\n\n• Membuat tiket gangguan IT\n• Memantau status penanganan\n• Melihat riwayat masalah\n• Mencari solusi di Knowledge Base\n\nJika ada kendala, hubungi IT Support.",
            ],
        ];

        foreach ($infos as $info) {
            OfficeInfo::create(array_merge($info, ['author_id' => $admin->id]));
        }

        $this->command->info('✅ Office Info seeded!');
    }
}