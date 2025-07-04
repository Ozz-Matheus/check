<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesSeeder::class,
            ProcessSeeder::class,
            SubProcessSeeder::class,
            UserHasSubProcessesSeeder::class,
            DocTypeSeeder::class,
            DocExpirationSeeder::class,
            DocEndingSeeder::class,
            StatusSeeder::class,
            ActionOriginSeeder::class,
            ActionTypeSeeder::class,
            ActionAnalysisCauseSeeder::class,
            ActionVerificationMethodSeeder::class,
            AuditCriteriaSeeder::class,
            RiskSeeder::class,
            ControlSeeder::class,
            // RolePermissionSeeder::class,
        ]);
    }
}
