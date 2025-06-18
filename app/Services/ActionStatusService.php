<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Status;
use App\Notifications\ActionCanceledNotice;
use Filament\Notifications\Notification;

/**
 * Servicio de cambio de estado de para las acciones
 */
class ActionStatusService
{
    public function statusChangesInActions(Action $action, string $status): bool
    {
        $statusChangeId = Status::byContextAndTitle('action', $status)?->id;
        $proposalId = Status::byContextAndTitle('action', 'proposal')?->id;

        if (! $statusChangeId) {
            return false;
        }

        // Solo permitir cambio a "in execution" si el estado actual es "proposal"
        if ($status === 'in_execution' && $action->status_id !== $proposalId) {
            return false;
        }

        return $action->update(['status_id' => $statusChangeId]);
    }

    public function closingDateInActions(Action $action)
    {
        return $action->update(['actual_closing_date' => now()->format('Y-m-d')]);
    }

    public function statusAssignmentCanceled(Action $action, array $data)
    {
        $statusActionChangeId = Status::byContextAndTitle('action', 'canceled')?->id;

        $action->update(['status_id' => $statusActionChangeId, 'reason_for_cancellation' => $data['reason_for_cancellation']]);

        $action->responsibleBy->notify(new ActionCanceledNotice($action));

        return $action;
    }

    // Métodos auxiliares privados.

    private function StatusNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }
}
