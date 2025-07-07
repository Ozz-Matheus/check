<?php

namespace Database\Seeders;

use App\Models\ControlType;
use Illuminate\Database\Seeder;

class ControlTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* Controles para el primer riesgo de cadena de suministros */
        ControlType::factory()->create([
            'title' => 'Realizar auditorías regulares de proveedores',
            'risk_id' => 1, // Relación con el riesgo "Riesgo de interrupción en la cadena de suministro"
        ]);
        ControlType::factory()->create([
            'title' => 'Diversificar los proveedores',
            'risk_id' => 1,
        ]);
        ControlType::factory()->create([
            'title' => 'Establecer acuerdos de suministro de emergencia',
            'risk_id' => 1,
        ]);

        /* Controles para el segundo riesgo de cadena de suministros */
        ControlType::factory()->create([
            'title' => 'Monitoreo constante de inventarios',
            'risk_id' => 2, // Relación con el riesgo "Riesgo de escasez de materias primas"
        ]);
        ControlType::factory()->create([
            'title' => 'Firmar acuerdos con proveedores alternativos',
            'risk_id' => 2,
        ]);

        /* Controles para el tercer riesgo de cadena de suministros */
        ControlType::factory()->create([
            'title' => 'Establecer indicadores de desempeño de entregas',
            'risk_id' => 3, // Relación con el riesgo "Riesgo de incumplimiento de plazos de entrega"
        ]);
        ControlType::factory()->create([
            'title' => 'Contratar un coordinador logístico',
            'risk_id' => 3,
        ]);

        /* Controles para el primer riesgo de calidad */
        ControlType::factory()->create([
            'title' => 'Implementar revisiones de calidad más estrictas',
            'risk_id' => 4, // Relación con el riesgo "Riesgo de defectos en los productos"
        ]);
        ControlType::factory()->create([
            'title' => 'Capacitar al personal en calidad de producto',
            'risk_id' => 4,
        ]);

        /* Controles para el segundo riesgo de calidad */
        ControlType::factory()->create([
            'title' => 'Automatizar los controles de calidad',
            'risk_id' => 5, // Relación con el riesgo "Riesgo de fallas en los controles de calidad"
        ]);
        ControlType::factory()->create([
            'title' => 'Realizar auditorías internas frecuentes',
            'risk_id' => 5,
        ]);

        /* Controles para el primer riesgo de gestión financiera */
        ControlType::factory()->create([
            'title' => 'Establecer un plan de gestión de riesgos financieros',
            'risk_id' => 6, // Relación con el riesgo "Riesgo de insolvencia financiera"
        ]);
        ControlType::factory()->create([
            'title' => 'Revisar los estados financieros mensualmente',
            'risk_id' => 6,
        ]);

        /* Controles para el segundo riesgo de gestión financiera */
        ControlType::factory()->create([
            'title' => 'Implementar un sistema de alerta temprana para fraudes',
            'risk_id' => 7, // Relación con el riesgo "Riesgo de fraude financiero"
        ]);
        ControlType::factory()->create([
            'title' => 'Revisión y validación de transacciones por auditoría externa',
            'risk_id' => 7,
        ]);
    }
}
