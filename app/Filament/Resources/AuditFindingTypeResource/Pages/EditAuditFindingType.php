<?php

namespace App\Filament\Resources\AuditFindingTypeResource\Pages;

use App\Filament\Resources\AuditFindingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditFindingType extends EditRecord
{
    protected static string $resource = AuditFindingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
