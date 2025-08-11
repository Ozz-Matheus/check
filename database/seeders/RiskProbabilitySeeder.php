<?php

namespace Database\Seeders;

use App\Models\RiskProbability;
use Illuminate\Database\Seeder;

class RiskProbabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskProbability::factory()->create([
            'title' => 'Raro',
            'score' => 1,
        ]);
        RiskProbability::factory()->create([
            'title' => 'Improbable',
            'score' => 2,
        ]);
        RiskProbability::factory()->create([
            'title' => 'Posible',
            'score' => 3,
        ]);
        RiskProbability::factory()->create([
            'title' => 'Probable',
            'score' => 4,
        ]);
        RiskProbability::factory()->create([
            'title' => 'Casi Seguro',
            'score' => 5,
        ]);
    }
}
