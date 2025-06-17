<?php

namespace App\Filament\Resources\CorrectiveResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\CorrectiveResource;
use App\Services\ActionService;
use App\Services\ActionStatusService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewCorrective extends ViewRecord
{
    protected static string $resource = CorrectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('view')
                ->label('View action completion')
                ->button()
                ->color('primary')
                ->authorize(fn ($record) => app(ActionService::class)->canViewActionEnding($record->status_id))
                ->url(fn ($record) => ActionResource::getUrl('action_endings.view', [
                    'action_id' => $record->id,
                    'record' => $record->ending->id,
                ])),

            Action::make('finish')
                ->label('End action')
                ->button()
                ->color('success')
                ->authorize(
                    fn ($record) => app(ActionService::class)->canFinishAction($record)

                )
                ->url(fn ($record) => ActionResource::getUrl('action_endings.create', [
                    'action_id' => $record->id,
                ])),

            Action::make('cancel')
                ->label('Cancel')
                ->button()
                ->color('danger')
                ->authorize(
                    fn ($record) => app(ActionService::class)->canCancelAction($record)
                )
                ->form([
                    Textarea::make('reason_for_cancellation')
                        ->label('Reason for cancellation')
                        ->required()
                        ->placeholder('Write the reason for cancellation'),
                ])
                ->action(function ($record, array $data) {
                    app(ActionStatusService::class)->statusAssignmentCanceled($record, $data);
                    redirect(CorrectiveResource::getUrl('index'));
                }),

            Action::make('back')
                ->label('Return')
                ->url(fn (): string => CorrectiveResource::getUrl('index'))
                ->button()
                ->color('grey'),

            // Actions\DeleteAction::make(),
        ];
    }
}
