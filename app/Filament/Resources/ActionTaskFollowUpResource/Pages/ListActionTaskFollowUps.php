<?php

namespace App\Filament\Resources\ActionTaskFollowUpResource\Pages;

use App\Filament\Resources\ActionTaskFollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActionTaskFollowUps extends ListRecords
{
    protected static string $resource = ActionTaskFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
