<?php

namespace App\Console\Commands;

use App\Models\Doc;
use App\Notifications\DocDeadlineNotice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotifyDocDeadlines extends BaseDeadlineCommand
{
    protected $signature = 'notify:doc-limit_dates';

    protected $description = 'Notifica vencimiento de documentos.';

    // Sobrescribimos la columna porque Docs usa un nombre diferente
    protected string $dateColumn = 'central_expiration_date';

    protected function getQuery(): Builder
    {
        return Doc::with(['createdBy', 'latestVersion']);
    }

    protected function getRecipients(Model $record): array
    {
        return $record->createdBy ? [$record->createdBy] : [];
    }

    protected function getNotification(Model $record): mixed
    {
        return new DocDeadlineNotice($record);
    }
}
