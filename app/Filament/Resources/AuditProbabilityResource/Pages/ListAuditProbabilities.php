<?php

namespace App\Filament\Resources\AuditProbabilityResource\Pages;

use App\Filament\Resources\AuditProbabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditProbabilities extends ListRecords
{
    protected static string $resource = AuditProbabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
