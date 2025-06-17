<?php

namespace App\Services;

use app\Models\Action;
use App\Models\Status;

/**
 * Servicio para las acciones
 */
class ActionService
{
    public function canCancelAction(Action $action)
    {

        $finishedActionStatusId = Status::byTitle('finished')?->id;
        $canceledActionStatusId = Status::byTitle('canceled')?->id;
        $currentActionStatusId = $action->status_id;

        if ($currentActionStatusId === $finishedActionStatusId || $currentActionStatusId === $canceledActionStatusId) {
            return false;
        }

        return auth()->id() === $action->registered_by_id;
    }

    public function canFinishAction(Action $action): bool
    {
        $expectedActionStatusId = Status::byTitle('in_execution')?->id;
        $currentActionStatusId = $action->status_id;

        if ($currentActionStatusId !== $expectedActionStatusId) {
            return false;
        }

        if (auth()->id() !== $action->responsible_by_id) {
            return false;
        }

        $completedActionStatusId = Status::byTitle('completed')?->id;

        $hasUncompletedTasks = $action->tasks()
            ->where('status_id', '!=', $completedActionStatusId)
            ->exists();

        return ! $hasUncompletedTasks;
    }

    public function canViewActionEnding(int $statusId)
    {
        $expectedActionStatusId = Status::byTitle('finished')?->id;

        return $statusId === $expectedActionStatusId;
    }
}
