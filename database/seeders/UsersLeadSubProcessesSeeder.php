<?php

namespace Database\Seeders;

use App\Models\SubProcess;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersLeadSubProcessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRoleName = 'super_admin';

        // 1. Encontrar al usuario superadministrador.
        $superAdmin = User::whereHas('roles', function ($query) use ($superAdminRoleName) {
            $query->where('name', $superAdminRoleName);
        })->first();

        // 2. Si no existe el superadministrador, no hacer nada.
        if (! $superAdmin) {
            return;
        }

        // 3. Obtener todos los subprocesos.
        $subProcesses = SubProcess::pluck('id');

        // 4. Preparar los datos para la inserción.
        $data = $subProcesses->map(function ($subProcessId) use ($superAdmin) {
            return [
                'user_id' => $superAdmin->id,
                'sub_process_id' => $subProcessId,
            ];
        })->all();

        // 5. Insertar las relaciones en la tabla pivote.
        DB::table('users_lead_subprocesses')->insert($data);
    }
}
