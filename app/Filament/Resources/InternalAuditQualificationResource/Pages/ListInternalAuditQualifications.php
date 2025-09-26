<?php

namespace App\Filament\Resources\InternalAuditQualificationResource\Pages;

use App\Filament\Resources\InternalAuditQualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternalAuditQualifications extends ListRecords
{
    protected static string $resource = InternalAuditQualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
