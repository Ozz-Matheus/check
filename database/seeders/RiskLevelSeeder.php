<?php

namespace Database\Seeders;

use App\Models\RiskLevel;
use Illuminate\Database\Seeder;

class RiskLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskLevel::factory()->create([
            'title' => 'Bajo',
            'min' => 1,
            'max' => 4,
        ]);
        RiskLevel::factory()->create([
            'title' => 'Moderado',
            'min' => 5,
            'max' => 12,
        ]);
        RiskLevel::factory()->create([
            'title' => 'Alto',
            'min' => 13,
            'max' => 24,
        ]);
        RiskLevel::factory()->create([
            'title' => 'Crítico',
            'min' => 25,
            'max' => 40,
        ]);
    }
}
