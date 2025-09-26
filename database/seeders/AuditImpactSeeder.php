<?php

namespace Database\Seeders;

use App\Models\AuditImpact;
use Illuminate\Database\Seeder;

class AuditImpactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditImpact::factory()->create([
            'title' => 'Insignificante',
            'score' => 1,
        ]);
        AuditImpact::factory()->create([
            'title' => 'Bajo',
            'score' => 2,
        ]);
        AuditImpact::factory()->create([
            'title' => 'Moderado',
            'score' => 3,
        ]);
        AuditImpact::factory()->create([
            'title' => 'Alto',
            'score' => 4,
        ]);
        AuditImpact::factory()->create([
            'title' => 'Crítico',
            'score' => 5,
        ]);
    }
}
