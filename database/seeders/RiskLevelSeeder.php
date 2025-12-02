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
        RiskLevel::factory()->createMany([
            ['title' => 'Bajo',      'min' => 1,  'max' => 39,  'color' => 'success'],
            ['title' => 'Medio',     'min' => 40,  'max' => 99, 'color' => 'yellow'],
            ['title' => 'Alto',      'min' => 100, 'max' => 239, 'color' => 'warning'],
            ['title' => 'Muy alto',  'min' => 240, 'max' => 400, 'color' => 'danger'],
        ]);
    }
}
