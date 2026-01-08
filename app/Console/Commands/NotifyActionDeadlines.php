<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Notifications\ActionDeadlineNotice;
use App\Notifications\ActionExpiredNotice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotifyActionDeadlines extends BaseDeadlineCommand
{
    protected $signature = 'notify:action-limit_dates';

    protected $description = 'Notifica responsables de acciones.';

    protected function getQuery(): Builder
    {
        return Action::with('responsibleBy');
    }

    protected function getRecipients(Model $record): array
    {
        return $record->responsibleBy ? [$record->responsibleBy] : [];
    }

    protected function getWarningNotification(Model $record): mixed
    {
        return new ActionDeadlineNotice($record); // Próxima a Vencer
    }

    protected function getExpiredNotification(Model $record): mixed
    {
        return new ActionExpiredNotice($record);  // Vencida
    }
}
