<?php

namespace App\Filament\Resources\AuditControlResource\Pages;

use App\Filament\Resources\AuditControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditControls extends ListRecords
{
    protected static string $resource = AuditControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
