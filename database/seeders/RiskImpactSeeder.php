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
            ['title' => 'Insignificante', 'weight' => 50],
            ['title' => 'Menor', 'weight' => 100],
            ['title' => 'Moderado', 'weight' => 200],
            ['title' => 'Mayor', 'weight' => 300],
            ['title' => 'Crítico', 'weight' => 400],
        ]);
    }
}
