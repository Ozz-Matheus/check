<?php

namespace Database\Seeders;

use App\Models\Risk;
use Illuminate\Database\Seeder;

class RiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* Riesgos para cadena de suministros */
        Risk::factory()->create([
            'title' => 'Riesgo de interrupción en la cadena de suministro',
            'process_id' => 1,
        ]);
        Risk::factory()->create([
            'title' => 'Riesgo de escasez de materias primas',
            'process_id' => 1,
        ]);
        Risk::factory()->create([
            'title' => 'Riesgo de incumplimiento de plazos de entrega',
            'process_id' => 1,
        ]);

        /* Riesgos para calidad */
        Risk::factory()->create([
            'title' => 'Riesgo de defectos en los productos',
            'process_id' => 2,
        ]);
        Risk::factory()->create([
            'title' => 'Riesgo de fallas en los controles de calidad',
            'process_id' => 2,
        ]);

        /* Riesgos para gestión financiera */
        Risk::factory()->create([
            'title' => 'Riesgo de insolvencia financiera',
            'process_id' => 3,
        ]);
        Risk::factory()->create([
            'title' => 'Riesgo de fraude financiero',
            'process_id' => 3,
        ]);

        /* Riesgos para gestión humana */
        Risk::factory()->create([
            'title' => 'Riesgo de alta rotación de empleados',
            'process_id' => 4,
        ]);
        Risk::factory()->create([
            'title' => 'Riesgo de incumplimiento de leyes laborales',
            'process_id' => 4,
        ]);

        /* Riesgos para Investigación y desarrollo */
        Risk::factory()->create([
            'title' => 'Riesgo de fracaso en el lanzamiento de productos',
            'process_id' => 5,
        ]);
        Risk::factory()->create([
            'title' => 'Riesgo de obsolescencia tecnológica',
            'process_id' => 5,
        ]);
    }
}
