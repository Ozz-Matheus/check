<?php

namespace App\Console\Commands;

use App\Models\Doc;
use App\Notifications\DocDeadlineNotice;
use App\Notifications\DocExpiredNotice;
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
        $leaders = $record->createdBy?->getLeadersToSubProcess($record->sub_process_id);

        if ($leaders) {
            return collect($leaders->all())
                ->unique('id')
                ->all();
        }

        return [];
    }

    protected function getWarningNotification(Model $record): mixed
    {
        return new DocDeadlineNotice($record); // Próximo a Vencer
    }

    protected function getExpiredNotification(Model $record): mixed
    {
        return new DocExpiredNotice($record); // Vencido
    }
}
