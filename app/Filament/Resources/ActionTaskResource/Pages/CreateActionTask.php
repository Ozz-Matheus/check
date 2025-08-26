<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ActionTaskResource;
use App\Models\Action;
use App\Models\ActionTask;
use App\Models\Status;
use App\Notifications\TaskAssignedNotice;
use App\Services\ActionStatusService;
use Filament\Resources\Pages\CreateRecord;

class CreateActionTask extends CreateRecord
{
    protected static string $resource = ActionTaskResource::class;

    public ?int $action_id = null;

    public ?Action $actionModel = null;

    public function mount(): void
    {
        parent::mount();
        $this->action_id = request()->route('action');
        $this->actionModel = Action::findOrFail($this->action_id);
    }

    protected function handleRecordCreation(array $data): ActionTask
    {

        $task = ActionTask::create([
            'action_id' => $this->action_id,
            'title' => $data['title'],
            'detail' => $data['detail'],
            'responsible_by_id' => $data['responsible_by_id'],
            'start_date' => $data['start_date'],
            'deadline' => $data['deadline'],
            'status_id' => Status::byContextAndTitle('task', 'pending')?->id,
        ]);

        $task->responsibleBy?->notify(new TaskAssignedNotice($task));

        app(ActionStatusService::class)->statusChangesInActions($this->actionModel, 'in_execution');

        return $task;
    }

    protected function getRedirectUrl(): string
    {
        return ActionResource::getUrl('task.view', [
            'action' => $this->action_id,
            'record' => $this->record->id,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return $this->actionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            ActionResource::getUrl('view', ['record' => $this->action_id]) => 'Action',
            ActionResource::getUrl('task.create', ['action' => $this->action_id]) => 'Task',
            false => 'Create',
        ];
    }
}
