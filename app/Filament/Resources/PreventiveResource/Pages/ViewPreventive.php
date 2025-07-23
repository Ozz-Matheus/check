<?php

namespace App\Filament\Resources\PreventiveResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\PreventiveResource;
use App\Services\ActionService;
use App\Services\ActionStatusService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewPreventive extends ViewRecord
{
    protected static string $resource = PreventiveResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('view')
                ->label(__('View action completion'))
                ->button()
                ->color('primary')
                ->authorize(fn ($record) => app(ActionService::class)->canViewActionEnding($record->status_id))
                ->url(fn ($record) => ActionResource::getUrl('action_endings.view', [
                    'action' => $record->id,
                    'record' => $record->ending->id,
                ])),

            Action::make('finish')
                ->label(__('End action'))
                ->button()
                ->color('success')
                ->authorize(
                    fn ($record) => app(ActionService::class)->canFinishAction($record)

                )
                ->url(fn ($record) => ActionResource::getUrl('action_endings.create', [
                    'action' => $record->id,
                ])),

            Action::make('cancel')
                ->label(__('Cancel'))
                ->button()
                ->color('danger')
                ->authorize(
                    fn ($record) => app(ActionService::class)->canCancelAction($record)
                )
                ->form([
                    Textarea::make('reason_for_cancellation')
                        ->label(__('Reason for cancellation'))
                        ->required()
                        ->placeholder(__('Write the reason for cancellation')),
                ])
                ->action(function ($record, array $data) {
                    app(ActionStatusService::class)->statusAssignmentCanceled($record, $data);
                    redirect(PreventiveResource::getUrl('index'));
                }),

            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => PreventiveResource::getUrl('index'))
                ->button()
                ->color('gray'),

            // Actions\DeleteAction::make(),
        ];
    }
}
