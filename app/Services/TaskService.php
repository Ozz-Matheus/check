<?php

namespace App\Services;

use App\Models\ActionTask;
use App\Notifications\TaskFinishedNotice;
use Filament\Notifications\Notification;

/**
 * Servicio para las tareas
 */
class TaskService
{
    protected array $statusIds;

    public function __construct(StatusService $statusService)
    {
        $this->statusIds = $statusService->getActionAndTaskStatuses();
    }

    // Comprueba si se puede ver el boton de crear la tarea
    public function canViewCreateTask(int $statusId): bool
    {
        return in_array($statusId, [$this->statusIds['pending'], $this->statusIds['in_execution']]);
    }

    // Comprueba si se puede ver el boton de cerrar la tarea
    public function canViewFinishTask(ActionTask $actionTask): bool
    {
        if ($actionTask->action->status_id === $this->statusIds['canceled']) {
            return false;
        }

        return ! $actionTask->finished &&
            in_array($actionTask->status_id, [$this->statusIds['in_execution'], $this->statusIds['overdue'], $this->statusIds['extemporaneous']]);
    }

    // Cierra la tarea
    public function finishTask(ActionTask $actionTask, array $data): bool
    {
        $actionTask->responsibleBy->notify(new TaskFinishedNotice($actionTask));

        $updates = [
            'real_closing_date' => now()->format('Y-m-d'),
            'finished' => true,
        ];

        if (in_array($actionTask->status_id, [$this->statusIds['overdue'], $this->statusIds['extemporaneous']])) {
            $updates['status_id'] = $this->statusIds['extemporaneous'];
            $updates['extemporaneous_reason'] = $data['extemporaneous_reason'] ?? null;
        } else {
            $updates['status_id'] = $this->statusIds['completed'];
        }

        return $actionTask->update($updates);
    }

    // Comprueba si la usuario puede ver el boton de cancelar la tarea
    public function canViewCancelTask(ActionTask $actionTask)
    {
        return in_array($actionTask->status_id, [
            $this->statusIds['pending'],
            $this->statusIds['in_execution'],
        ]) &&
            $actionTask->action->status_id !== $this->statusIds['canceled'];
    }

    // Cancela la tarea
    public function cancelTask(ActionTask $actionTask, array $data)
    {
        $actionTask->update([
            'status_id' => $this->statusIds['canceled'],
            'reason_for_cancellation' => $data['reason_for_cancellation'],
            'cancellation_date' => now()->format('Y-m-d'),
        ]);

        return $actionTask;
    }

    // Comprueba si se puede ver el boton de crear el seguimiento de la tarea
    public function canViewCreateTaskFollowUp(ActionTask $actionTask): bool
    {
        return ! $actionTask->finished && $actionTask->action->status_id !== $this->statusIds['canceled'] && $actionTask->status_id !== $this->statusIds['canceled'];
    }

    // Actualiza el estado de la tarea al crear un seguimiento
    public function updateTaskStatus(ActionTask $actionTask): bool
    {
        $updates = [];

        if ($actionTask->real_start_date === null) {
            $updates['real_start_date'] = today();
        }

        if ($actionTask->limit_date->isPast()) {
            $updates['status_id'] = $this->statusIds['extemporaneous'];

            return $actionTask->update($updates);
        }

        switch ($actionTask->status_id) {
            case $this->statusIds['pending']:
                $updates['status_id'] = $this->statusIds['in_execution'];
                break;

            case $this->statusIds['overdue']:
                $updates['status_id'] = $this->statusIds['extemporaneous'];
                break;
        }

        return ! empty($updates) ? $actionTask->update($updates) : false;
    }

    // Cambia el estado de la acción a en ejecución si la tarea es la primera creada
    public function changeActionStatusToExecution(ActionTask $actionTask): bool
    {
        if ($actionTask->action->status_id !== $this->statusIds['pending']) {
            return false;
        }

        return $actionTask->action->update(['status_id' => $this->statusIds['in_execution']]);
    }

    // Métodos auxiliares privados.
    private function sendTaskNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }
}
