<?php

namespace App\Filament\Resources\AuditCriteriaResource\Pages;

use App\Filament\Resources\AuditCriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditCriterias extends ListRecords
{
    protected static string $resource = AuditCriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
