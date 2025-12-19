<?php

namespace App\Console\Commands;

use App\Models\ActionTask;
use App\Notifications\TaskDeadlineNotice;
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

        if ($record->responsibleBy) {
            $recipients[] = $record->responsibleBy;
        }

        if ($record->action && $record->action->responsibleBy) {
            $recipients[] = $record->action->responsibleBy;
        }

        return $recipients;
    }

    protected function getNotification(Model $record): mixed
    {
        return new TaskDeadlineNotice($record);
    }
}
