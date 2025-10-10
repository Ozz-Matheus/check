<?php

namespace App\Filament\Resources\ActionTaskFollowUpResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ActionTaskFollowUpResource;
use App\Models\ActionTask;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewActionTaskFollowUp extends ViewRecord
{
    protected static string $resource = ActionTaskFollowUpResource::class;

    public ?int $action_task_id = null;

    public ?ActionTask $actionTaskModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->action_task_id = request()->route('task');
        $this->actionTaskModel = ActionTask::findOrFail($this->action_task_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => ActionResource::getUrl('task.view', [
                    'action' => $record->actionTask->action_id,
                    'record' => $record->action_task_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            ActionResource::getUrl('view', ['record' => $this->actionTaskModel->action_id]) => __('Action'),
            ActionResource::getUrl('task.view', [
                'action' => $this->actionTaskModel->action_id,
                'record' => $this->actionTaskModel->id,
            ]) => __('Action Task'),
            ActionResource::getUrl('task-follow-up.view', [
                'task' => $this->actionTaskModel->id,
                'record' => $this->record->id,
            ]) => __('Task Follow Up'),
            false => __('View'),
        ];
    }
}
