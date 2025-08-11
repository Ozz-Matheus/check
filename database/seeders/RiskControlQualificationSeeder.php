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
            'title' => 'Ausencia de control/Incontrolable',
            'score' => 0,
        ]);
        RiskControlQualification::factory()->create([
            'title' => 'Débil/Básico',
            'score' => 20,
        ]);
        RiskControlQualification::factory()->create([
            'title' => 'Moderado/Intermedio',
            'score' => 50,
        ]);
        RiskControlQualification::factory()->create([
            'title' => 'Fuerte/Desarrollado',
            'score' => 80,
        ]);
    }
}
