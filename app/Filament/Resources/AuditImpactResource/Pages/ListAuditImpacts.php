<?php

namespace App\Filament\Resources\AuditImpactResource\Pages;

use App\Filament\Resources\AuditImpactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditImpacts extends ListRecords
{
    protected static string $resource = AuditImpactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
