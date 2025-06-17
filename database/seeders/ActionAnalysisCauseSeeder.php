<?php

namespace Database\Seeders;

use App\Models\ActionAnalysisCause;
use Illuminate\Database\Seeder;

class ActionAnalysisCauseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ActionAnalysisCause::factory()->create([
            'title' => 'Diagrama de Espina de Pescado',
        ]);
        ActionAnalysisCause::factory()->create([
            'title' => 'Método de los 5 Porqués',
        ]);
        ActionAnalysisCause::factory()->create([
            'title' => 'RCA',
        ]);
        ActionAnalysisCause::factory()->create([
            'title' => 'Diagrama Causa-Efecto',
        ]);
    }
}
