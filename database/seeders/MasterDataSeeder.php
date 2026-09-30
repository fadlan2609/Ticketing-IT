<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Cabang
        $branches = [
            ['code' => 'KC-BDG', 'name' => 'KC Bandung', 'address' => 'Jl. Asia Afrika No. 100', 'phone' => '022-1234567'],
            ['code' => 'KC-JKT', 'name' => 'KC Jakarta', 'address' => 'Jl. Sudirman No. 50', 'phone' => '021-1234567'],
            ['code' => 'KC-SBY', 'name' => 'KC Surabaya', 'address' => 'Jl. Tunjungan No. 20', 'phone' => '031-1234567'],
        ];
        foreach ($branches as $b) {
            Branch::create($b);
        }

        // Divisi
        $divisions = [
            ['code' => 'IT', 'name' => 'Teknologi Informasi', 'description' => 'Divisi IT'],
            ['code' => 'OPS', 'name' => 'Operasional', 'description' => 'Divisi Operasional'],
            ['code' => 'PMS', 'name' => 'Pemasaran', 'description' => 'Divisi Pemasaran'],
            ['code' => 'AUD', 'name' => 'Audit Internal', 'description' => 'Divisi Audit'],
        ];
        foreach ($divisions as $d) {
            Division::create($d);
        }

        // Users
        $itDivision = Division::where('code', 'IT')->first();
        $opsDivision = Division::where('code', 'OPS')->first();
        $audDivision = Division::where('code', 'AUD')->first();
        $branchBandung = Branch::where('code', 'KC-BDG')->first();

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bprs.test',
            'nip' => 'ADM001',
            'phone' => '081200000001',
            'division_id' => $itDivision->id,
            'branch_id' => $branchBandung->id,
            'position' => 'IT Manager',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        // IT Support
        $itSupport = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bprs.test',
            'nip' => 'IT001',
            'phone' => '081200000002',
            'division_id' => $itDivision->id,
            'branch_id' => $branchBandung->id,
            'position' => 'IT Support',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $itSupport->assignRole('IT Support');

        // Requester
        $requester = User::create([
            'name' => 'Ahmad Teller',
            'email' => 'ahmad@bprs.test',
            'nip' => 'OPS001',
            'phone' => '081200000003',
            'division_id' => $opsDivision->id,
            'branch_id' => $branchBandung->id,
            'position' => 'Teller',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $requester->assignRole('Requester');

        // Supervisor
        $supervisor = User::create([
            'name' => 'Rina Supervisor',
            'email' => 'rina@bprs.test',
            'nip' => 'OPS002',
            'phone' => '081200000004',
            'division_id' => $opsDivision->id,
            'branch_id' => $branchBandung->id,
            'position' => 'Kepala Cabang',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $supervisor->assignRole('Supervisor');

        // Auditor
        $auditor = User::create([
            'name' => 'Dewi Auditor',
            'email' => 'dewi@bprs.test',
            'nip' => 'AUD001',
            'phone' => '081200000005',
            'division_id' => $audDivision->id,
            'branch_id' => $branchBandung->id,
            'position' => 'Internal Audit',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $auditor->assignRole('Auditor');

        $this->command->info('✅ Master data berhasil dibuat!');
    }
}