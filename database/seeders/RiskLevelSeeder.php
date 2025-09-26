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
            'max' => 5,
        ]);
        RiskLevel::factory()->create([
            'title' => 'Moderado',
            'min' => 6,
            'max' => 12,
        ]);
        RiskLevel::factory()->create([
            'title' => 'Alto',
            'min' => 13,
            'max' => 19,
        ]);
        RiskLevel::factory()->create([
            'title' => 'Crítico',
            'min' => 20,
            'max' => 25,
        ]);
    }
}
