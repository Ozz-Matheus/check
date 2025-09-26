<?php

namespace App\Filament\Resources\AuditControlClassificationResource\Pages;

use App\Filament\Resources\AuditControlClassificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditControlClassifications extends ListRecords
{
    protected static string $resource = AuditControlClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
