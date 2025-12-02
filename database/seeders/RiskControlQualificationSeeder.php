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
            'title' => 'Débil/Basico',
            'reduction_factor' => 20,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'middle',
            'title' => 'Moderado/Intermedio',
            'reduction_factor' => 50,
        ]);
        RiskControlQualification::factory()->create([
            'context' => 'max',
            'title' => 'Fuerte/Desarrollado',
            'reduction_factor' => 80,
        ]);
    }
}
