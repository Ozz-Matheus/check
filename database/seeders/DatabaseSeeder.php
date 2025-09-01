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
            StatusSeeder::class,
            // Documentos
            DocTypeSeeder::class,
            DocExpirationSeeder::class,
            DocEndingSeeder::class,
            // Acciones
            ActionTypeSeeder::class,
            ActionSourceSeeder::class,
            ActionAnalysisCauseSeeder::class,
            ActionVerificationMethodSeeder::class,
            // Riesgos
            RiskStrategicContextTypeSeeder::class,
            RiskStrategicContextSeeder::class,
            RiskCategorySeeder::class,
            RiskImpactSeeder::class,
            RiskProbabilitySeeder::class,
            RiskLevelSeeder::class,

            RiskControlPeriodicitySeeder::class,
            RiskControlTypeSeeder::class,
            RiskControlQualificationSeeder::class,
            // RolePermissionSeeder::class,
        ]);
    }
}
