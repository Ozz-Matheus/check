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
        // --------- Context: action_and_task ---------
        Status::factory()->create([
            'context' => 'action_and_task',
            'title' => 'pending',
            'label' => 'Pendiente',
            'color' => 'gray',
            'icon' => 'heroicon-o-pencil-square',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action_and_task',
            'title' => 'in_execution',
            'label' => 'En ejecución',
            'color' => 'indigo',
            'icon' => 'heroicon-o-clock',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action_and_task',
            'title' => 'completed',
            'label' => 'Completado',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action_and_task',
            'title' => 'overdue',
            'label' => 'Vencido',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action_and_task',
            'title' => 'extemporaneous',
            'label' => 'Extemporaneo',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
            'protected' => true,
        ]);
        Status::factory()->create([
            'context' => 'action_and_task',
            'title' => 'canceled',
            'label' => 'Cancelado',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
            'protected' => true,
        ]);
    }
}
