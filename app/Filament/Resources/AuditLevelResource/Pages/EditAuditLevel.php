<?php

namespace App\Filament\Resources\AuditLevelResource\Pages;

use App\Filament\Resources\AuditLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditLevel extends EditRecord
{
    protected static string $resource = AuditLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
