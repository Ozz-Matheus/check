<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Services\ActionService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewAction extends ViewRecord
{
    protected static string $resource = ActionResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('view')
                ->label('View action completion')
                ->button()
                ->color('primary')
                ->authorize(auth()->user()->can('view_action::ending'))
                ->visible(fn ($record) => app(ActionService::class)->canViewActionEnding($record))
                ->url(fn ($record) => ActionResource::getUrl('ending.view', [
                    'action' => $record->id,
                    'record' => $record->ending->id,
                ])),

            Action::make('finish')
                ->label('End action')
                ->button()
                ->color('success')
                ->authorize(fn ($record) => auth()->id() === $record->responsible_by_id && auth()->user()->can('create_action::ending'))
                ->visible(fn ($record) => app(ActionService::class)->canViewFinishAction($record))
                ->url(fn ($record) => ActionResource::getUrl('ending.create', [
                    'action' => $record->id,
                ])),

            Action::make('cancel')
                ->label('Cancel')
                ->button()
                ->color('danger')
                ->authorize(fn ($record) => auth()->id() === $record->responsible_by_id)
                ->visible(fn ($record) => app(ActionService::class)->canViewCancelAction($record))
                ->form([
                    Textarea::make('reason_for_cancellation')
                        ->label('Reason for cancellation')
                        ->required()
                        ->placeholder('Write the reason for cancellation'),
                ])
                ->action(function ($record, array $data) {
                    app(ActionService::class)->cancelAction($record, $data);
                    redirect(ActionResource::getUrl('index'));
                }),

            Action::make('back')
                ->label('Return')
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->record->ActionSubtitle();
    }
}
