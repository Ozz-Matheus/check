<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ListActionTasks extends ViewRecord
{
    protected static string $resource = ActionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
