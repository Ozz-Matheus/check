<?php

namespace App\Filament\Resources\AuditNatureOfControlResource\Pages;

use App\Filament\Resources\AuditNatureOfControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditNatureOfControls extends ListRecords
{
    protected static string $resource = AuditNatureOfControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
