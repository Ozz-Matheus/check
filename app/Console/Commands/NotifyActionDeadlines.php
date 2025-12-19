<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Notifications\ActionDeadlineNotice;
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

    protected function getNotification(Model $record): mixed
    {
        return new ActionDeadlineNotice($record);
    }
}
