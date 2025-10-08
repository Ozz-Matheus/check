<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ActionTaskResource;
use App\Models\Action;
use App\Services\StatusService;
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
                ->label(__('End task'))
                ->color('success')
                ->authorize(fn ($record) => $record->responsible_by_id === auth()->id())
                ->visible(fn ($record) => app(TaskService::class)->canViewFinishTask($record))
                ->form(
                    fn ($record) => in_array($record->status_id, [
                        app(StatusService::class)->getActionAndTaskStatuses()['overdue'],
                        app(StatusService::class)->getActionAndTaskStatuses()['extemporaneous'],
                    ]) || $record->limit_date < today()
                        ? [
                            \Filament\Forms\Components\Textarea::make('extemporaneous_reason')
                                ->label(__('Reason for delay'))
                                ->required(),
                        ]
                        : []
                )

                ->action(function ($record, array $data) {
                    app(TaskService::class)->finishTask($record, $data);
                    redirect(ActionResource::getUrl('view', [
                        'record' => $record->action_id,
                    ]));
                }),
            FilamentAction::make('cancel')
                ->label(__('Cancel'))
                ->color('danger')
                ->authorize(fn ($record) => auth()->id() === $record->action?->responsible_by_id)
                ->visible(fn ($record) => app(TaskService::class)->canViewCancelTask($record))
                ->form([
                    \Filament\Forms\Components\Textarea::make('reason_for_cancellation')
                        ->label(__('Reason for cancellation'))
                        ->required()
                        ->placeholder(__('Write the reason for cancellation')),
                ])
                ->action(function ($record, array $data) {
                    app(TaskService::class)->cancelTask($record, $data);
                    $this->redirect(ActionResource::getUrl('view', [
                        'record' => $record->action_id,
                    ]));
                }),

            FilamentAction::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => ActionResource::getUrl('view', [
                    'record' => $record->action_id,
                ]))
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
            ActionResource::getUrl('view', ['record' => $this->action_id]) => __('Action'),
            ActionResource::getUrl('task.view', [
                'action' => $this->action_id,
                'record' => $this->record->id,
            ]) => __('Task'),
            false => __('View'),
        ];
    }
}
