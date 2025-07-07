<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // --------- Context: doc ---------
        Status::factory()->create([
            'context' => 'doc',
            'title' => 'draft',
            'label' => 'Borrador',
            'color' => 'warning',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'doc',
            'title' => 'pending',
            'label' => 'Pendiente',
            'color' => 'indigo',
            'icon' => 'heroicon-o-clock',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'doc',
            'title' => 'approved',
            'label' => 'Aprobado',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'doc',
            'title' => 'rejected',
            'label' => 'Rechazado',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'doc',
            'title' => 'restore',
            'label' => 'Restaurar',
            'color' => 'gray',
            'icon' => 'heroicon-o-arrow-uturn-left',
            'protected' => true,
        ]);
        // --------- Context: action ---------
        Status::factory()->create([
            'context' => 'action',
            'title' => 'proposal',
            'label' => 'Propuesto',
            'color' => 'gray',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action',
            'title' => 'open',
            'label' => 'Abierto',
            'color' => 'gray',
            'icon' => 'heroicon-o-folder-open',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action',
            'title' => 'in_execution',
            'label' => 'En ejecución',
            'color' => 'indigo',
            'icon' => 'heroicon-o-clock',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action',
            'title' => 'finished',
            'label' => 'Finalizado',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action',
            'title' => 'canceled',
            'label' => 'Cancelado',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
            'protected' => true,
        ]);
        // --------- Context: task ---------
        Status::factory()->create([
            'context' => 'task',
            'title' => 'pending',
            'label' => 'Pendiente',
            'color' => 'gray',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'task',
            'title' => 'in_execution',
            'label' => 'En ejecución',
            'color' => 'indigo',
            'icon' => 'heroicon-o-clock',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'task',
            'title' => 'completed',
            'label' => 'Completado',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'task',
            'title' => 'overdue',
            'label' => 'Vencido',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'task',
            'title' => 'extemporaneous',
            'label' => 'Extemporaneo',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
            'protected' => true,
        ]);
        // --------- Context: audit ---------
        Status::factory()->create([
            'context' => 'audit',
            'title' => 'planned',
            'label' => 'Planificada',
            'color' => 'gray',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'audit',
            'title' => 'in_execution',
            'label' => 'En ejecución',
            'color' => 'indigo',
            'icon' => 'heroicon-o-clock',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'audit',
            'title' => 'closed',
            'label' => 'Cerrado',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
        // --------- Context: control ---------
        Status::factory()->create([
            'context' => 'control',
            'title' => 'unrated',
            'label' => 'Sin calificar',
            'color' => 'gray',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'control',
            'title' => 'pass',
            'label' => 'Pasa',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'control',
            'title' => 'fail',
            'label' => 'No pasa',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'control',
            'title' => 'partial',
            'label' => 'Parcial',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
            'protected' => true,
        ]);
        // --------- Context: finding ---------
        Status::factory()->create([
            'context' => 'finding',
            'title' => 'open',
            'label' => 'Abierto',
            'color' => 'gray',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'finding',
            'title' => 'in_execution',
            'label' => 'En ejecución',
            'color' => 'indigo',
            'icon' => 'heroicon-o-clock',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'finding',
            'title' => 'closed',
            'label' => 'Cerrado',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
    }
}
