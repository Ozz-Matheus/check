<?php

namespace App\Filament\Resources\IAndAEventTypeResource\Pages;

use App\Filament\Resources\IAndAEventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIAndAEventTypes extends ListRecords
{
    protected static string $resource = IAndAEventTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
