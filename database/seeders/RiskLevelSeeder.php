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
            ['title' => 'Bajo',      'min' => 1,  'max' => 5,  'color' => 'success'],
            ['title' => 'Medio',     'min' => 6,  'max' => 12, 'color' => 'yellow'],
            ['title' => 'Alto',      'min' => 13, 'max' => 19, 'color' => 'warning'],
            ['title' => 'Muy alto',  'min' => 20, 'max' => 25, 'color' => 'danger'],
        ]);
    }
}
