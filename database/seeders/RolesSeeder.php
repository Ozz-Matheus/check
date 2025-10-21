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
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $panelRole = Role::firstOrCreate(['name' => 'panel_user']);

        // Crear permisos base
        $permissions = [
            'view_role',
            'view_any_role',
            'create_role',
            'update_role',
        ];

        // Crear y asegurar permisos
        $permissionModels = collect($permissions)->map(function ($name) {
            return Permission::firstOrCreate(['name' => $name]);
        });

        // Asignar permisos reales al rol SuperAdmin
        $superAdminRole->syncPermissions($permissionModels->pluck('name')->toArray());

        // Crear usuario admin
        $SuperAdmin = User::firstOrCreate(
            ['email' => 's@th.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('s@th.com'),
            ]
        );
        $SuperAdmin->assignRole($superAdminRole);

    }
}
