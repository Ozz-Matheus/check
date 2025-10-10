<?php

namespace App\Filament\Resources\ActionFollowUpResource\Pages;

use App\Filament\Resources\ActionFollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActionFollowUps extends ListRecords
{
    protected static string $resource = ActionFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
