<?php

namespace Database\Seeders;

use App\Models\SubProcess;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserHasSubProcessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = 'super_admin'; // o el nombre exacto del rol

        $subProcesses = SubProcess::pluck('id');

        $users = User::whereDoesntHave('roles', function ($query) use ($superAdminRole) {
            $query->where('name', $superAdminRole);
        })->pluck('id');

        $data = [];

        foreach ($users as $user) {
            foreach ($subProcesses as $subProcess) {
                $data[] = [
                    'user_id' => $user,
                    'sub_process_id' => $subProcess,
                ];
            }
        }

        DB::table('user_has_sub_processes')->insert($data);
    }
}
