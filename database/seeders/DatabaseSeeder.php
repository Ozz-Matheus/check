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
            // Generales
            HeadquarterSeeder::class,
            RolesSeeder::class,
            ProcessSeeder::class,
            SubProcessSeeder::class,
            UserHasSubProcessesSeeder::class,
            StatusSeeder::class,
            PrioritySeeder::class,
            // Documentos
            DocTypeSeeder::class,
            DocStorageSeeder::class,
            DocRecoverySeeder::class,
            DocDispositionSeeder::class,
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
            // Auditorias
            InternalAuditQualificationSeeder::class,
            // Audit items
            AuditSubProcessActivitySeeder::class,
            // Audit controls
            AuditNatureOfControlSeeder::class,
            AuditEffectTypeSeeder::class,
            AuditControlClassificationSeeder::class,
            AuditImpactSeeder::class,
            AuditProbabilitySeeder::class,
            AuditLevelSeeder::class,
            // Audit findings
            AuditFindingTypeSeeder::class,
            // Productos de los Proveedors
            // * //SupplierProductSeeder::class,
            // Proveedor novedades
            SupplierIssueCauseSeeder::class,
            // Incidentes y accidentes
            IAndAEventTypeSeeder::class,
            // RolePermissionSeeder::class,
        ]);
    }
}
