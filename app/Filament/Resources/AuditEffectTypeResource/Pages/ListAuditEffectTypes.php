<?php

namespace App\Filament\Resources\AuditEffectTypeResource\Pages;

use App\Filament\Resources\AuditEffectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditEffectTypes extends ListRecords
{
    protected static string $resource = AuditEffectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
