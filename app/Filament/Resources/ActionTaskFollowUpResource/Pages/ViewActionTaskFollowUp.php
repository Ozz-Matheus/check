<?php

namespace App\Filament\Resources\ActionTaskFollowUpResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ActionTaskFollowUpResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewActionTaskFollowUp extends ViewRecord
{
    protected static string $resource = ActionTaskFollowUpResource::class;

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
}
