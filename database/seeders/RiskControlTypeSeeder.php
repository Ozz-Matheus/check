<?php

namespace Database\Seeders;

use App\Models\RiskControlType;
use Illuminate\Database\Seeder;

class RiskControlTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskControlType::factory()->create([
            'title' => 'Automatico',
        ]);
        RiskControlType::factory()->create([
            'title' => 'Semiautomatico',
        ]);
        RiskControlType::factory()->create([
            'title' => 'Manual',
        ]);
    }
}
