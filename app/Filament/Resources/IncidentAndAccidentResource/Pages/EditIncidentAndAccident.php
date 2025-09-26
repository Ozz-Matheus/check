<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Pages;

use App\Filament\Resources\IncidentAndAccidentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncidentAndAccident extends EditRecord
{
    protected static string $resource = IncidentAndAccidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
