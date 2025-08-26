<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ActionTaskResource;
use App\Models\Action;
use App\Models\Status;
use App\Services\TaskService;
use Filament\Actions\Action as FilamentAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActionTask extends ViewRecord
{
    protected static string $resource = ActionTaskResource::class;

    public ?int $action_id = null;

    public ?Action $actionModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->action_id = request()->route('action');
        $this->actionModel = Action::findOrFail($this->action_id);
    }

    protected function getHeaderActions(): array
    {
        return [

            FilamentAction::make('finish_task')
                ->label('End task')
                ->button()
                ->color('success')
                ->authorize(fn ($record) => $record->responsible_by_id === auth()->id())
                ->visible(fn ($record) => app(TaskService::class)->canViewCloseTask($record))
                ->form(
                    fn ($record) => $record->status?->id === Status::byContextAndTitle('task', 'extemporaneous')?->id
                        ? [
                            \Filament\Forms\Components\Textarea::make('extemporaneous_reason')
                                ->label('Reason for delay')
                                ->required(),
                        ]
                        : []
                )
                ->action(function ($record, array $data) {
                    app(TaskService::class)->closeTask($record, $data);
                    redirect(ActionResource::getUrl('view', [
                        'record' => $record->action_id,
                    ]));
                }),

            FilamentAction::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => ActionResource::getUrl('view', [
                    'record' => $record->action_id,
                ]))
                ->button()
                ->color('gray'),

        ];
    }

    public function getSubheading(): ?string
    {
        return $this->actionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            ActionResource::getUrl('view', ['record' => $this->action_id]) => 'Action',
            ActionResource::getUrl('task.view', [
                'action' => $this->action_id,
                'record' => $this->record->id,
            ]) => 'Task',
            false => 'View',
        ];
    }
}
