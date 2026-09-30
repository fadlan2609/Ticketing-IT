<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permission
        $permissions = [
            // User Management
            'user.view', 'user.create', 'user.edit', 'user.delete',
            // Tiket
            'ticket.view', 'ticket.create', 'ticket.edit', 'ticket.delete',
            'ticket.assign', 'ticket.update-status', 'ticket.close',
            'ticket.escalate', 'ticket.reopen',
            // SLA
            'sla.view', 'sla.manage',
            // Knowledge Base
            'kb.view', 'kb.create', 'kb.edit', 'kb.delete',
            // Report
            'report.view', 'report.export',
            // Aset
            'asset.view', 'asset.create', 'asset.edit', 'asset.delete',
            // Audit
            'audit.view',
            // Konfigurasi
            'setting.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Role: Super Admin (semua permission)
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Role: IT Support
        $itSupport = Role::create(['name' => 'IT Support']);
        $itSupport->givePermissionTo([
            'ticket.view', 'ticket.edit', 'ticket.update-status',
            'ticket.close', 'ticket.escalate', 'kb.view', 'kb.create',
            'asset.view', 'report.view',
        ]);

        // Role: Supervisor
        $supervisor = Role::create(['name' => 'Supervisor']);
        $supervisor->givePermissionTo([
            'ticket.view', 'ticket.assign', 'ticket.escalate',
            'kb.view', 'report.view', 'report.export', 'sla.view',
            'asset.view',
        ]);

        // Role: Requester
        $requester = Role::create(['name' => 'Requester']);
        $requester->givePermissionTo([
            'ticket.view', 'ticket.create', 'ticket.reopen',
            'kb.view',
        ]);

        // Role: Auditor (read-only)
        $auditor = Role::create(['name' => 'Auditor']);
        $auditor->givePermissionTo([
            'ticket.view', 'report.view', 'report.export',
            'audit.view', 'kb.view', 'asset.view',
        ]);

        $this->command->info('✅ Roles & Permissions berhasil dibuat!');
    }
}