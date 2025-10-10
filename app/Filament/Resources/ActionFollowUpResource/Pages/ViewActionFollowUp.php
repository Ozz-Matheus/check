<?php

namespace App\Filament\Resources\ActionFollowUpResource\Pages;

use App\Filament\Resources\ActionFollowUpResource;
use App\Filament\Resources\ActionResource;
use App\Models\Action;
use Filament\Actions\Action as FilamentAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActionFollowUp extends ViewRecord
{
    protected static string $resource = ActionFollowUpResource::class;

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
            FilamentAction::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => ActionResource::getUrl('view', [
                    'record' => $record->action_id,
                ]))
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            ActionResource::getUrl('view', ['record' => $this->action_id]) => __('Action'),
            ActionResource::getUrl('action-follow-up.view', [
                'action' => $this->action_id,
                'record' => $this->record->id,
            ]) => __('Action Follow Ups'),
            false => __('View'),
        ];
    }
}
