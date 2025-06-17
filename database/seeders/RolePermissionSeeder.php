<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $roleAdmin = Role::findByName('admin');
        $roleStandard = Role::findByName('standard');
        $rolePanelUser = Role::findByName('panel_user');

        // Permisos
        $permissionViewAnyVersion = Permission::findByName('view_any_doc::version');
        $permissionCreateVersion = Permission::findByName('create_doc::version');

        $permissionViewAnyDoc = Permission::findByName('view_any_doc');
        $permissionCreateDoc = Permission::findByName('create_doc');

        $permissionViewAnyProcess = Permission::findByName('view_any_process');
        $permissionCreateProcess = Permission::findByName('create_process');
        $permissionUpdateProcess = Permission::findByName('update_process');

        $permissionViewAnySubProcess = Permission::findByName('view_any_sub::process');
        $permissionCreateSubProcess = Permission::findByName('create_sub::process');
        $permissionUpdateSubProcess = Permission::findByName('update_sub::process');

        $permissionViewAnyDocType = Permission::findByName('view_any_doc::type');
        $permissionCreateDocType = Permission::findByName('create_doc::type');
        $permissionUpdateDocType = Permission::findByName('update_doc::type');

        $permissionViewAnyStatus = Permission::findByName('view_any_status');
        $permissionUpdateStatus = Permission::findByName('update_status');

        $permissionViewAnyUser = Permission::findByName('view_any_user');
        $permissionCreateUser = Permission::findByName('create_user');
        $permissionUpdateUser = Permission::findByName('update_user');

        // Permisos Admin
        $roleAdmin->givePermissionTo($permissionViewAnyVersion);
        $roleAdmin->givePermissionTo($permissionCreateVersion);

        $roleAdmin->givePermissionTo($permissionViewAnyDoc);
        $roleAdmin->givePermissionTo($permissionCreateDoc);

        $roleAdmin->givePermissionTo($permissionViewAnyProcess);
        $roleAdmin->givePermissionTo($permissionCreateProcess);
        $roleAdmin->givePermissionTo($permissionUpdateProcess);

        $roleAdmin->givePermissionTo($permissionViewAnySubProcess);
        $roleAdmin->givePermissionTo($permissionCreateSubProcess);
        $roleAdmin->givePermissionTo($permissionUpdateSubProcess);

        $roleAdmin->givePermissionTo($permissionViewAnyDocType);
        $roleAdmin->givePermissionTo($permissionCreateDocType);
        $roleAdmin->givePermissionTo($permissionUpdateDocType);

        $roleAdmin->givePermissionTo($permissionViewAnyStatus);
        $roleAdmin->givePermissionTo($permissionUpdateStatus);

        $roleAdmin->givePermissionTo($permissionViewAnyUser);
        $roleAdmin->givePermissionTo($permissionCreateUser);
        $roleAdmin->givePermissionTo($permissionUpdateUser);

        // Permisos Standard
        $roleStandard->givePermissionTo($permissionViewAnyVersion);
        $roleStandard->givePermissionTo($permissionCreateVersion);
        $roleStandard->givePermissionTo($permissionViewAnyDoc);
        $roleStandard->givePermissionTo($permissionCreateDoc);

        // Permisos Panel User
        $rolePanelUser->givePermissionTo($permissionViewAnyDoc);
    }
}
