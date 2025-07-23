<?php

namespace Database\Seeders;

use App\Models\ProcessRisk;
use Illuminate\Database\Seeder;

class ProcessRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* Riesgos para cadena de suministros */
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de interrupción en la cadena de suministro',
            'process_id' => 1,
        ]);
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de escasez de materias primas',
            'process_id' => 1,
        ]);
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de incumplimiento de plazos de entrega',
            'process_id' => 1,
        ]);

        /* Riesgos para calidad */
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de defectos en los productos',
            'process_id' => 2,
        ]);
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de fallas en los controles de calidad',
            'process_id' => 2,
        ]);

        /* Riesgos para gestión financiera */
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de insolvencia financiera',
            'process_id' => 3,
        ]);
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de fraude financiero',
            'process_id' => 3,
        ]);

        /* Riesgos para gestión humana */
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de alta rotación de empleados',
            'process_id' => 4,
        ]);
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de incumplimiento de leyes laborales',
            'process_id' => 4,
        ]);

        /* Riesgos para Investigación y desarrollo */
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de fracaso en el lanzamiento de productos',
            'process_id' => 5,
        ]);
        ProcessRisk::factory()->create([
            'title' => 'Riesgo de obsolescencia tecnológica',
            'process_id' => 5,
        ]);
    }
}
