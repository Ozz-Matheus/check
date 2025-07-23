<?php

namespace App\Services;

use App\Models\ActionTask;
use App\Models\Status;
use App\Notifications\TaskCompletedNotice;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para las tareas
 */
class TaskService
{
    public function canCreateTask(int $responsibleId, int $statusId): bool
    {
        $statusProposal = Status::byContextAndTitle('action', 'proposal')?->id;
        $statusInExecution = Status::byContextAndTitle('action', 'in_execution')?->id;

        if (auth()->id() === $responsibleId && ($statusId === $statusProposal || $statusId === $statusInExecution)) {
            return true;
        }

        return false;
    }

    public function canCloseTask(ActionTask $actionTask)
    {
        $responsibleTaskId = $actionTask->responsible_by_id;

        $statusTaskInExecutionId = Status::byContextAndTitle('task', 'in_execution')?->id;

        $statusActionCanceledId = Status::byContextAndTitle('action', 'canceled')?->id;

        $currentActionStatusId = $actionTask->action->status_id;

        if (auth()->id() !== $responsibleTaskId) {
            return false;
        }
        if ($actionTask->status_id !== $statusTaskInExecutionId) {
            return false;
        }

        return $currentActionStatusId !== $statusActionCanceledId;
    }

    public function canTaskUploadFollowUp(ActionTask $actionTask)
    {

        $responsibleTaskId = $actionTask->responsible_by_id;

        $statusTaskCompletedId = Status::byContextAndTitle('task', 'completed')?->id;
        $statusTaskExtemporaneousId = Status::byContextAndTitle('task', 'extemporaneous')?->id;

        $statusActionCanceledId = Status::byContextAndTitle('action', 'canceled')?->id;

        $currentActionStatusId = $actionTask->action->status_id;

        if (auth()->id() !== $responsibleTaskId) {
            return false;
        }
        if (($actionTask->status_id === $statusTaskCompletedId || $actionTask->status_id === $statusTaskExtemporaneousId)) {
            return false;
        }

        return $currentActionStatusId !== $statusActionCanceledId;
    }

    public function createComment(ActionTask $actionTask, array $data): void
    {

        $actionTask->comments()->create($data);

        $this->updateTaskStatus($actionTask);
        $this->assignActualStartDate($actionTask);

        $this->taskNotification(__('Comment saved successfully'));
    }

    public function createFiles(ActionTask $actionTask, array $data): void
    {
        foreach ($data['path'] ?? [] as $path) {

            $fileName = $data['name'][$path] ?? basename($path);

            $fileMetadata = [
                'name' => $fileName,
                'path' => $path,
                'mime_type' => Storage::disk('public')->mimeType($path),
                'size' => Storage::disk('public')->size($path),
            ];

            $actionTask->files()->create($fileMetadata);
        }

        $this->updateTaskStatus($actionTask);
        $this->assignActualStartDate($actionTask);

        $this->taskNotification(__('Support files uploaded successfully'));
    }

    public function closeTask(ActionTask $actionTask): bool
    {
        $statusTitle = now()->lessThanOrEqualTo($actionTask->deadline) ? 'completed' : 'extemporaneous';
        $statusTaskId = Status::byContextAndTitle('task', $statusTitle)?->id;

        // Solo si quedó como 'completed' y existe responsable de la acción
        if ($actionTask->responsible_by_id && $statusTitle === 'completed') {
            $actionTask->responsibleBy->notify(new TaskCompletedNotice($actionTask));
        }

        return $statusTaskId ? $actionTask->update(['status_id' => $statusTaskId, 'actual_closing_date' => now()->format('Y-m-d')]) : false;
    }

    // Métodos auxiliares privados.

    private function updateTaskStatus(ActionTask $actionTask): bool
    {
        $currentTaskStatusId = $actionTask->status_id;

        $pendingTaskStatusId = Status::byContextAndTitle('task', 'pending')?->id;
        $inExecutionTaskStatusId = Status::byContextAndTitle('task', 'in_execution')?->id;

        if ($currentTaskStatusId === $pendingTaskStatusId && $inExecutionTaskStatusId !== null) {
            return $actionTask->update(['status_id' => $inExecutionTaskStatusId]);
        }

        return false;
    }

    private function assignActualStartDate(ActionTask $actionTask)
    {
        $actualStartDate = $actionTask->actual_start_date;

        if ($actualStartDate === null) {

            return $actionTask->update(['actual_start_date' => now()->format('Y-m-d')]);
        }

        return false;
    }

    private function taskNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }
}
