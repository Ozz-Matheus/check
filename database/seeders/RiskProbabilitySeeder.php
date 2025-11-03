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
            ['title' => 'Raro', 'weight' => 1],
            ['title' => 'Improbable', 'weight' => 2],
            ['title' => 'Posible', 'weight' => 3],
            ['title' => 'Probable', 'weight' => 4],
            ['title' => 'Casi Seguro', 'weight' => 5],
        ]);
    }
}
