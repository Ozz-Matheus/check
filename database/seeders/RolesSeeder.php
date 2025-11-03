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
        $panelRole = Role::firstOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);
        $supplierRole = Role::firstOrCreate(['name' => 'supplier', 'guard_name' => 'web']);

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

        // Crear usuario SuperAdmin
        $superAdmin = User::firstOrCreate(
            ['email' => 's@ht.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('s@ht.com'),
            ]
        );

        $superAdmin->assignRole($superAdminRole);
    }
}
