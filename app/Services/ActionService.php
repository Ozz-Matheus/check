<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Headquarter;
use App\Models\SubProcess;
use Illuminate\Support\Facades\DB;

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

    public function generateCode($subProcessId, $headquarterId): string
    {
        $headquarterId = $headquarterId ?? auth()->user()->headquarter_id;

        return DB::transaction(function () use ($subProcessId, $headquarterId) {

            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);
            $headquarter = Headquarter::lockForUpdate()->findOrFail($headquarterId);

            $count = Action::where('origin_type', null)
                ->where('sub_process_id', $subProcessId)
                ->where('headquarter_id', $headquarterId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "IND-{$subProcess->acronym}-{$consecutive}-{$headquarter->acronym}";
        });
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
            'cancellation_date' => today(),
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
