<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionTaskResource;
use App\Models\ActionTask;
use App\Models\Status;
use App\Notifications\TaskAssignedNotice;
use App\Services\ActionStatusService;
use App\Traits\HasActionContext;
use Filament\Resources\Pages\CreateRecord;

class CreateActionTask extends CreateRecord
{
    use HasActionContext;

    protected static string $resource = ActionTaskResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->loadActionContext();
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

        app(ActionStatusService::class)->statusChangesInActions($this->ActionModel, 'in_execution');

        return $task;
    }

    protected function getRedirectUrl(): string
    {
        return $this->ActionModel->getFilamentUrl();
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return $this->ActionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->ActionModel->getFilamentUrl() => ucfirst($this->ActionModel->type->name),
            false => 'Task',
        ];
    }
}
