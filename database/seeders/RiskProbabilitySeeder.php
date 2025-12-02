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
        RiskProbability::factory()->createMany([
            ['title' => 'Raro', 'weight' => 0.2],
            ['title' => 'Improbable', 'weight' => 0.4],
            ['title' => 'Posible', 'weight' => 0.6],
            ['title' => 'Probable', 'weight' => 0.8],
            ['title' => 'Casi Seguro', 'weight' => 1],
        ]);
    }
}
