<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Status;

/**
 * Servicio para las acciones
 */
class ActionService
{
    public function canCancelAction(Action $action)
    {

        $finishedStatusId = Status::byContextAndTitle('action', 'finished')?->id;
        $canceledStatusId = Status::byContextAndTitle('action', 'canceled')?->id;
        $currentStatusId = $action->status_id;

        if ($currentStatusId === $finishedStatusId || $currentStatusId === $canceledStatusId) {
            return false;
        }

        return auth()->id() === $action->registered_by_id;
    }

    public function canViewFinishAction(Action $action): bool
    {

        $expectedActionStatusId = Status::byContextAndTitle('action', 'in_execution')?->id;
        $currentActionStatusId = $action->status_id;

        if ($currentActionStatusId !== $expectedActionStatusId) {
            return false;
        }

        $completedTaskStatusId = Status::byContextAndTitle('task', 'completed')?->id;
        $extemporaneouTaskStatusId = Status::byContextAndTitle('task', 'extemporaneous')?->id;

        $hasInvalidTasks = $action->tasks()
            ->whereNotIn('status_id', [$completedTaskStatusId, $extemporaneouTaskStatusId]) // Verifica si hay tareas fuera de estos dos estados
            ->exists();

        return ! $hasInvalidTasks;
    }

    public function canViewActionEnding(int $statusId)
    {
        $expectedStatusId = Status::byContextAndTitle('action', 'finished')?->id;

        return $statusId === $expectedStatusId;
    }
}
