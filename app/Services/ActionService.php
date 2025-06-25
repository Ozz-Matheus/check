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

    public function canFinishAction(Action $action): bool
    {

        $expectedActionStatusId = Status::byContextAndTitle('action', 'in_execution')?->id;
        $currentActionStatusId = $action->status_id;

        if ($currentActionStatusId !== $expectedActionStatusId) {
            return false;
        }

        if (auth()->id() !== $action->responsible_by_id) {
            return false;
        }

        $completedTaskStatusId = Status::byContextAndTitle('task', 'completed')?->id;

        $hasUncompletedTasks = $action->tasks()
            ->where('status_id', '!=', $completedTaskStatusId)
            ->exists();

        return ! $hasUncompletedTasks;
    }

    public function canViewActionEnding(int $statusId)
    {
        $expectedStatusId = Status::byContextAndTitle('action', 'finished')?->id;

        return $statusId === $expectedStatusId;
    }
}
