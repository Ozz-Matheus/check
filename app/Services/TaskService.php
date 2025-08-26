<?php

namespace App\Services;

use App\Models\ActionTask;
use App\Models\Status;
use App\Notifications\TaskFinishedNotice;
use Filament\Notifications\Notification;

/**
 * Servicio para las tareas
 */
class TaskService
{
    private $statusIds;

    public function __construct()
    {
        $this->statusIds = [
            'proposal' => Status::byContextAndTitle('action', 'proposal')?->id,
            'in_execution_action' => Status::byContextAndTitle('action', 'in_execution')?->id,
            'canceled' => Status::byContextAndTitle('action', 'canceled')?->id,
            'pending' => Status::byContextAndTitle('task', 'pending')?->id,
            'in_execution_task' => Status::byContextAndTitle('task', 'in_execution')?->id,
            'overdue' => Status::byContextAndTitle('task', 'overdue')?->id,
            'extemporaneous' => Status::byContextAndTitle('task', 'extemporaneous')?->id,
            'completed' => Status::byContextAndTitle('task', 'completed')?->id,
        ];
    }

    public function canViewCreateTask(int $statusId): bool
    {
        return in_array($statusId, [$this->statusIds['proposal'], $this->statusIds['in_execution_action']]);
    }

    public function canViewCloseTask(ActionTask $actionTask): bool
    {
        if ($actionTask->action->status_id === $this->statusIds['canceled']) {
            return false;
        }

        return ! $actionTask->finished &&
            in_array($actionTask->status_id, [$this->statusIds['in_execution_task'], $this->statusIds['extemporaneous']]);
    }

    public function closeTask(ActionTask $actionTask, array $data): bool
    {
        $actionTask->responsibleBy->notify(new TaskFinishedNotice($actionTask));

        $updates = [
            'actual_closing_date' => now()->format('Y-m-d'),
            'finished' => true,
        ];

        if ($actionTask->status_id === $this->statusIds['extemporaneous']) {
            $updates['extemporaneous_reason'] = $data['extemporaneous_reason'] ?? null;
        } else {
            $updates['status_id'] = $this->statusIds['completed'];
        }

        return $actionTask->update($updates);
    }

    public function canViewCreateTaskFollowUp(ActionTask $actionTask): bool
    {
        return ! $actionTask->finished && $actionTask->action->status_id !== $this->statusIds['canceled'];
    }

    public function updateTaskStatus(ActionTask $actionTask): bool
    {
        $updates = [];

        if ($actionTask->actual_start_date === null) {
            $updates['actual_start_date'] = now()->format('Y-m-d');
        }

        switch ($actionTask->status_id) {
            case $this->statusIds['pending']:
                $updates['status_id'] = $this->statusIds['in_execution_task'];
                break;

            case $this->statusIds['overdue']:
                $updates['status_id'] = $this->statusIds['extemporaneous'];
                break;
        }

        return ! empty($updates) ? $actionTask->update($updates) : false;
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
