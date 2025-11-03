<?php

namespace Database\Seeders;

use App\Models\RiskControlQualification;
use Illuminate\Database\Seeder;

class RiskControlQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskControlQualification::factory()->create([
            'context' => 'min',
            'title' => 'Ausencia de control/Incontrolable',
            'reduction_factor' => 0,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'middle',
            'title' => 'Necesita mejorar',
            'reduction_factor' => 20,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'middle',
            'title' => 'Parcialmente eficaz',
            'reduction_factor' => 30,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'middle',
            'title' => 'Generalmente eficaz',
            'reduction_factor' => 60,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'middle',
            'title' => 'Eficaz',
            'reduction_factor' => 80,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'max',
            'title' => 'Altamente eficaz',
            'reduction_factor' => 90,
        ]);
    }
}
