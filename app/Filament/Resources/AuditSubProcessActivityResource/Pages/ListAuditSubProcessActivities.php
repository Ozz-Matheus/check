<?php

namespace App\Filament\Resources\AuditSubProcessActivityResource\Pages;

use App\Filament\Resources\AuditSubProcessActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditSubProcessActivities extends ListRecords
{
    protected static string $resource = AuditSubProcessActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
