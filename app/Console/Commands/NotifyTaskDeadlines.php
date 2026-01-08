<?php

namespace App\Console\Commands;

use App\Models\ActionTask;
use App\Notifications\TaskDeadlineNotice;
use App\Notifications\TaskExpiredNotice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotifyTaskDeadlines extends BaseDeadlineCommand
{
    protected $signature = 'notify:task-limit_dates';

    protected $description = 'Notifica responsables de tareas.';

    protected function getQuery(): Builder
    {
        return ActionTask::with(['responsibleBy', 'action.responsibleBy']);
    }

    protected function getRecipients(Model $record): array
    {
        $recipients = [];

        // Responsable de la Tarea
        if ($record->responsibleBy) {
            $recipients[] = $record->responsibleBy;
        }

        // Responsable de la Acción
        if ($record->action && $record->action->responsibleBy) {
            $recipients[] = $record->action->responsibleBy;
        }

        // Filtramos por ID para evitar duplicados
        return collect($recipients)->unique('id')->all();
    }

    protected function getWarningNotification(Model $record): mixed
    {
        return new TaskDeadlineNotice($record); // Próxima a Vencer
    }

    protected function getExpiredNotification(Model $record): mixed
    {
        return new TaskExpiredNotice($record); // Vencida
    }
}
