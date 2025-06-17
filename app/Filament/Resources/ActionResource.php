<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;

class ActionResource extends Resource
{
    public static function getPages(): array
    {
        return [
            'action_endings.create' => \App\Filament\Resources\ActionEndingResource\Pages\CreateActionEnding::route('/{action_id}/endings/create'),
            'action_endings.view' => \App\Filament\Resources\ActionEndingResource\Pages\ViewActionEnding::route('/{action_id}/endings/{record}'),
            'action_tasks.create' => \App\Filament\Resources\ActionTaskResource\Pages\CreateActionTask::route('/{action_id}/tasks/create'),
            'action_tasks.view' => \App\Filament\Resources\ActionTaskResource\Pages\ViewActionTask::route('/{action_id}/tasks/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
