<?php

namespace App\Services;

use App\Models\Action;

/**
 * Servicio para las acciones
 */
class ActionService
{
    protected array $statusIds;

    public function __construct(StatusService $statusService)
    {
        $this->statusIds = $statusService->getActionAndTaskStatuses();
    }

    // Comprueba si se puede ver el boton de cancelar la accion
    public function canViewCancelAction(Action $action)
    {
        return ! in_array($action->status_id, [$this->statusIds['completed'], $this->statusIds['canceled'], $this->statusIds['extemporaneous']]);
    }

    // Cancela la accion
    public function cancelAction(Action $action, array $data)
    {
        return $action->update([
            'status_id' => $this->statusIds['canceled'],
            'reason_for_cancellation' => $data['reason_for_cancellation'],
            'cancellation_date' => now()->format('Y-m-d'),
        ]);
    }

    // Comprueba si se puede ver el boton de finalizar la accion
    public function canViewFinishAction(Action $action): bool
    {
        if (! in_array($action->status_id, [$this->statusIds['in_execution'], $this->statusIds['overdue']])) {
            return false;
        }

        $hasInvalidTasks = $action->tasks()
            ->whereNotIn('status_id', [
                $this->statusIds['completed'],
                $this->statusIds['extemporaneous'],
                $this->statusIds['canceled'],
            ]) // Verifica si hay tareas fuera de estos dos estados
            ->exists();

        return ! $hasInvalidTasks;
    }

    // Comprueba si se puede ver el boton de ver la finalizacion de la accion
    public function canViewActionEnding(Action $action): bool
    {
        return in_array($action->status_id, [$this->statusIds['completed'], $this->statusIds['extemporaneous']]) && $action->ending()->exists();
    }

    // Comprueba si se puede ver el boton de crear tarea y seguimiento
    public function canViewCreateTaskAndFollowUp(int $statusId): bool
    {
        return in_array($statusId, [$this->statusIds['pending'], $this->statusIds['in_execution']]);
    }

    // Cambia el estado de la acción a en ejecución si esta pendiente
    public function changeActionStatusToExecution(Action $action): bool
    {
        if ($action->status_id !== $this->statusIds['pending']) {
            return false;
        }

        return $action->update(['status_id' => $this->statusIds['in_execution']]);
    }
}
