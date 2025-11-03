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
        RiskImpact::factory()->createMany([
            ['title' => 'Insignificante', 'weight' => 1],
            ['title' => 'Menor', 'weight' => 2],
            ['title' => 'Moderado', 'weight' => 3],
            ['title' => 'Mayor', 'weight' => 4],
            ['title' => 'Crítico', 'weight' => 5],
        ]);
    }
}
