<?php

namespace Database\Seeders;

use App\Models\RiskImpact;
use Illuminate\Database\Seeder;

class RiskImpactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskImpact::factory()->create([
            'title' => 'Insignificante',
            'score' => 1,
        ]);
        RiskImpact::factory()->create([
            'title' => 'Bajo',
            'score' => 2,
        ]);
        RiskImpact::factory()->create([
            'title' => 'Moderado',
            'score' => 3,
        ]);
        RiskImpact::factory()->create([
            'title' => 'Alto',
            'score' => 4,
        ]);
        RiskImpact::factory()->create([
            'title' => 'Crítico',
            'score' => 5,
        ]);
    }
}
