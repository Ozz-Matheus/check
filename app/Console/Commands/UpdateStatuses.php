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

    protected $description = 'Actualiza a vencido las acciones y tareas vencidas.';

    public function handle(): int
    {
        $this->logToSchedulerFile('Iniciando actualización de estados vencidos');

        $updatedActions = OverdueService::markAsOverdue(Action::class);
        $updatedTasks = OverdueService::markAsOverdue(ActionTask::class);

        $this->info("Acciones vencidas actualizadas: {$updatedActions}");
        $this->info("Tareas vencidas actualizadas: {$updatedTasks}");

        $this->logToSchedulerFile('Finalizó actualización de estados vencidos');

        return Command::SUCCESS;
    }
}
