<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\ActionTask;
use App\Services\OverdueService;
use App\Traits\LogsToSchedulerFile;
use Illuminate\Console\Command;

class UpdateStatuses extends Command
{
    use LogsToSchedulerFile;

    protected $signature = 'statuses:update';

    protected $description = 'Actualiza a "Vencido" las acciones y tareas cuya fecha límite ha pasado.';

    public function handle(): int
    {
        $this->logToSchedulerFile('Iniciando actualización de estados vencidos...');

        try {
            // Actualizar el estado de Acciones y tareas.
            $updatedActions = OverdueService::markAsOverdue(Action::class);
            $updatedTasks = OverdueService::markAsOverdue(ActionTask::class);

            // Logueamos solo si hubo cambios para no llenar el archivo de ruido
            if ($updatedActions > 0 || $updatedTasks > 0) {
                $this->info("Actualización completada: {$updatedActions} acciones, {$updatedTasks} tareas.");
                $this->logToSchedulerFile("✅ Cambios realizados: {$updatedActions} acciones y {$updatedTasks} tareas vencieron.");
            } else {
                $this->info('Sin registros vencidos.');
                // Opcional: comentar esta línea si no quieres logs vacíos
                // $this->logToSchedulerFile('Sin registros por vencer.');
            }

        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());
            $this->logToSchedulerFile('⚠️ ERROR actualizando estados: '.$e->getMessage());

            return Command::FAILURE;
        }

        $this->logToSchedulerFile('Finalizó actualización de estados.');

        return Command::SUCCESS;
    }
}
