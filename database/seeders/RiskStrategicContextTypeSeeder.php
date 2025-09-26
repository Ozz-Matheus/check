<?php

namespace Database\Seeders;

use App\Models\RiskStrategicContextType;
use Illuminate\Database\Seeder;

class RiskStrategicContextTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskStrategicContextType::factory()->create([
            'name' => 'internal',
            'label' => 'Interno',
        ]);
        RiskStrategicContextType::factory()->create([
            'name' => 'external',
            'label' => 'Externo',
        ]);
    }
}
