<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Pages;

use App\Filament\Resources\IncidentAndAccidentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncidentAndAccidents extends ListRecords
{
    protected static string $resource = IncidentAndAccidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
