<?php

namespace Database\Seeders;

use App\Models\AuditSubProcessActivity;
use Illuminate\Database\Seeder;

class AuditSubProcessActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditSubProcessActivity::factory()->createMany([
            [
                'process_id' => 1,
                'sub_process_id' => 1,
                'title' => 'Revisión de Políticas y Procedimientos',
            ],
            [
                'process_id' => 1,
                'sub_process_id' => 2,
                'title' => 'Evaluación de Controles Internos',
            ],
            [
                'process_id' => 2,
                'sub_process_id' => 3,
                'title' => 'Análisis de Riesgos',
            ],
            [
                'process_id' => 2,
                'sub_process_id' => 4,
                'title' => 'Verificación de Cumplimiento Normativo',
            ],
            [
                'process_id' => 3,
                'sub_process_id' => 5,
                'title' => 'Inspección de Seguridad Física',
            ],
            [
                'process_id' => 3,
                'sub_process_id' => 6,
                'title' => 'Pruebas de Recuperación ante Desastres',
            ],
        ]);
    }
}
