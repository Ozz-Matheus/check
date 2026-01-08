<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $riskRole = Role::firstOrCreate(['name' => 'risk', 'guard_name' => 'web']);
        $auditRole = Role::firstOrCreate(['name' => 'audit', 'guard_name' => 'web']);
        $incidentAndAccidentRole = Role::firstOrCreate(['name' => 'incident_and_accident', 'guard_name' => 'web']);
        $supplierIssueRole = Role::firstOrCreate(['name' => 'supplier_issue', 'guard_name' => 'web']);
        $supplierPanelRole = Role::firstOrCreate(['name' => 'supplier', 'guard_name' => 'web']);
        $panelRole = Role::firstOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);

        // Crear permisos base
        $permissions = [
            'view_role',
            'view_any_role',
            'create_role',
            'update_role',
        ];

        // Crear permisos si no existen
        $permissionModels = collect($permissions)->map(function ($name) {
            return Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        });

        // Asignar permisos al rol SuperAdmin
        $superAdminRole->givePermissionTo($permissionModels);

        // Mail

        $mail = 'admin@'.config('tenancy.central_domains')[0];

        // Crear usuario SuperAdmin
        $superAdmin = User::firstOrCreate(
            ['email' => $mail],
            [
                'name' => 'Super Admin',
                'password' => bcrypt($mail),
            ]
        );

        $superAdmin->assignRole($superAdminRole);
    }
}
