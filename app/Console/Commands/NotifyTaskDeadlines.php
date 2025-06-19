<?php

namespace App\Console\Commands;

use App\Models\ActionTask;
use App\Notifications\TaskDeadlineNotice;
use App\Traits\LogsToSchedulerFile;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NotifyTaskDeadlines extends Command
{
    use LogsToSchedulerFile;

    protected $signature = 'notify:task-deadlines';

    protected $description = 'Notifica a los responsables de tareas que vencen hoy o en 10 días.';

    public function handle(): int
    {
        $today = Carbon::today();
        $inTenDays = Carbon::today()->addDays(10);

        $tareas = ActionTask::whereDate('deadline', $today)
            ->orWhereDate('deadline', $inTenDays)
            ->get();

        $this->logToSchedulerFile('Iniciando revisión de tareas con vencimiento');

        foreach ($tareas as $task) {
            $recipients = [];

            if ($task->responsible) {
                $recipients[] = $task->responsible;
            }

            if ($task->action && $task->action->responsibleBy) {
                $recipients[] = $task->action->responsibleBy;
            }

            foreach ($recipients as $user) {
                $user->notify(new TaskDeadlineNotice($task));
                $this->info("Notificación enviada a {$user->email} para tarea ID {$task->id}");
            }
        }

        $this->logToSchedulerFile('Finalizó revisión de tareas');

        return Command::SUCCESS;
    }
}
