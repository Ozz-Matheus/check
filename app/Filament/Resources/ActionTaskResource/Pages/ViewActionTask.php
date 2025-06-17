<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionTaskResource;
use App\Models\Action;
use App\Services\TaskService;
use Filament\Actions\Action as FilamentAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActionTask extends ViewRecord
{
    protected static string $resource = ActionTaskResource::class;

    public ?int $action_id = null;

    public ?Action $ActionModel = null;

    public ?string $ActionModelName = null;

    public ?string $ActionModelResource = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);

        $this->action_id = request()->route('action_id');

        $action = Action::findOrFail($this->action_id);

        $this->ActionModel = $action;

        $this->ActionModelName = ucfirst($action->type->name);

        $this->ActionModelResource = '\\App\\Filament\\Resources\\'.$this->ActionModelName.'Resource';
    }

    protected function getHeaderActions(): array
    {

        $resourceClass = $this->ActionModelResource;

        return [

            FilamentAction::make('finish_task')
                ->label('End task')
                ->button()
                ->color('success')
                ->authorize(fn ($record) => app(TaskService::class)->canCloseTask($record))
                ->action(function ($record) use ($resourceClass) {
                    app(TaskService::class)->closeTask($record);

                    redirect($resourceClass::getUrl('view', [
                        'record' => $record->action_id,
                    ]));
                }),

            FilamentAction::make('back')
                ->label('Return')
                ->url(fn ($record): string => $resourceClass::getUrl('view', [
                    'record' => $record->action->id,
                ]))
                ->button()
                ->color('gray'),

        ];
    }

    public function getSubheading(): ?string
    {
        return $this->ActionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->ActionModelResource::getUrl('view', ['record' => $this->action_id]) => $this->ActionModelName,
            false => 'View',
        ];
    }
}
