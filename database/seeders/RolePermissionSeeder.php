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

        // Permisos globales
        $permissionViewAnyUser = Permission::findByName('view_any_user');
        $permissionCreateUser = Permission::findByName('create_user');
        $permissionUpdateUser = Permission::findByName('update_user');

        $permissionViewAnyProcess = Permission::findByName('view_any_process');
        $permissionCreateProcess = Permission::findByName('create_process');
        $permissionUpdateProcess = Permission::findByName('update_process');

        $permissionViewAnySubProcess = Permission::findByName('view_any_sub::process');
        $permissionCreateSubProcess = Permission::findByName('create_sub::process');
        $permissionUpdateSubProcess = Permission::findByName('update_sub::process');

        $permissionViewAnyStatus = Permission::findByName('view_any_status');
        $permissionUpdateStatus = Permission::findByName('update_status');

        /* $permissionViewAnyFile = Permission::findByName('view_any_file');
        $permissionCreateFile = Permission::findByName('create_file');  */

        // Permisos documentales
        $permissionViewAnyDoc = Permission::findByName('view_any_doc');
        $permissionCreateDoc = Permission::findByName('create_doc');

        $permissionViewAnyVersion = Permission::findByName('view_any_doc::version');
        $permissionCreateVersion = Permission::findByName('create_doc::version');

        $permissionViewAnyDocType = Permission::findByName('view_any_doc::type');
        $permissionCreateDocType = Permission::findByName('create_doc::type');
        $permissionUpdateDocType = Permission::findByName('update_doc::type');

        // Permisos acciones
        // Permisos finalización de acción
        $permissionViewAnyActionEnding = Permission::findByName('view_any_action::ending');
        $permissionCreateActionEnding = Permission::findByName('create_action::ending');
        $permissionViewActionEnding = Permission::findByName('view_action::ending');

        // Improve
        $permissionViewAnyAction = Permission::findByName('view_any_action');
        $permissionCreateAction = Permission::findByName('create_action');
        $permissionViewAction = Permission::findByName('view_action');

        $permissionViewAnyActionType = Permission::findByName('view_any_action::type');
        // $permissionCreateActionType = Permission::findByName('create_action::type');

        $permissionViewAnyActionAnalysisCauses = Permission::findByName('view_any_action::analysis::cause');
        $permissionCreateActionAnalysisCauses = Permission::findByName('create_action::analysis::cause');
        $permissionUpdateActionAnalysisCauses = Permission::findByName('update_action::analysis::cause');

        $permissionViewAnyActionVerificationMethod = Permission::findByName('view_any_action::verification::method');
        $permissionCreateActionVerificationMethod = Permission::findByName('create_action::verification::method');
        $permissionUpdateActionVerificationMethod = Permission::findByName('update_action::verification::method');

        // Permisos tareas
        $permissionViewAnyActionTask = Permission::findByName('view_any_action::task');
        $permissionCreateActionTask = Permission::findByName('create_action::task');
        $permissionViewActionTask = Permission::findByName('view_action::task');

        /* $permissionViewAnyActionTaskComment = Permission::findByName('view_any_action::task::comment');
        $permissionCreateActionTaskComment = Permission::findByName('create_action::task::comment'); */

        /* Asignación de permisos */
        // Permisos globales
        // Admin
        $roleAdmin->givePermissionTo($permissionViewAnyUser);
        $roleAdmin->givePermissionTo($permissionCreateUser);
        $roleAdmin->givePermissionTo($permissionUpdateUser);

        $roleAdmin->givePermissionTo($permissionViewAnyProcess);
        $roleAdmin->givePermissionTo($permissionCreateProcess);
        $roleAdmin->givePermissionTo($permissionUpdateProcess);

        $roleAdmin->givePermissionTo($permissionViewAnySubProcess);
        $roleAdmin->givePermissionTo($permissionCreateSubProcess);
        $roleAdmin->givePermissionTo($permissionUpdateSubProcess);

        $roleAdmin->givePermissionTo($permissionViewAnyStatus);
        $roleAdmin->givePermissionTo($permissionUpdateStatus);

        /* $roleAdmin->givePermissionTo($permissionViewAnyFile);
        $roleAdmin->givePermissionTo($permissionCreateFile); */

        // Permisos documentales
        // Admin
        $roleAdmin->givePermissionTo($permissionViewAnyDoc);
        $roleAdmin->givePermissionTo($permissionCreateDoc);

        $roleAdmin->givePermissionTo($permissionViewAnyVersion);
        $roleAdmin->givePermissionTo($permissionCreateVersion);
        //
        $roleAdmin->givePermissionTo($permissionViewAnyDocType);
        $roleAdmin->givePermissionTo($permissionCreateDocType);
        $roleAdmin->givePermissionTo($permissionUpdateDocType);

        // Standard
        $roleStandard->givePermissionTo($permissionViewAnyDoc);
        $roleStandard->givePermissionTo($permissionCreateDoc);

        $roleStandard->givePermissionTo($permissionViewAnyVersion);
        $roleStandard->givePermissionTo($permissionCreateVersion);

        // Permisos acciones
        // Admin
        $roleAdmin->givePermissionTo($permissionViewAnyActionEnding);
        $roleAdmin->givePermissionTo($permissionCreateActionEnding);
        $roleAdmin->givePermissionTo($permissionViewActionEnding);

        $roleAdmin->givePermissionTo($permissionViewAnyAction);
        $roleAdmin->givePermissionTo($permissionCreateAction);
        $roleAdmin->givePermissionTo($permissionViewAction);
        //
        $roleAdmin->givePermissionTo($permissionViewAnyActionType);
        // $roleAdmin->givePermissionTo($permissionCreateActionType);

        $roleAdmin->givePermissionTo($permissionViewAnyActionAnalysisCauses);
        $roleAdmin->givePermissionTo($permissionCreateActionAnalysisCauses);
        $roleAdmin->givePermissionTo($permissionUpdateActionAnalysisCauses);

        $roleAdmin->givePermissionTo($permissionViewAnyActionVerificationMethod);
        $roleAdmin->givePermissionTo($permissionCreateActionVerificationMethod);
        $roleAdmin->givePermissionTo($permissionUpdateActionVerificationMethod);

        // Standard
        $roleStandard->givePermissionTo($permissionViewAnyActionEnding);
        $roleStandard->givePermissionTo($permissionCreateActionEnding);
        $roleStandard->givePermissionTo($permissionViewActionEnding);

        $roleStandard->givePermissionTo($permissionViewAnyAction);
        $roleStandard->givePermissionTo($permissionCreateAction);
        $roleStandard->givePermissionTo($permissionViewAction);

        // Permisos tareas
        // Admin
        $roleAdmin->givePermissionTo($permissionViewAnyActionTask);
        $roleAdmin->givePermissionTo($permissionCreateActionTask);
        $roleAdmin->givePermissionTo($permissionViewActionTask);

        // Standard
        $roleStandard->givePermissionTo($permissionViewAnyActionTask);
        $roleStandard->givePermissionTo($permissionCreateActionTask);
        $roleStandard->givePermissionTo($permissionViewActionTask);

        // Permisos Panel User
        $rolePanelUser->givePermissionTo($permissionViewAnyDoc);
    }
}
