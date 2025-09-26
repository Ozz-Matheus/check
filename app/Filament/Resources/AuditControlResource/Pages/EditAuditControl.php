<?php

namespace App\Filament\Resources\AuditControlResource\Pages;

use App\Filament\Resources\AuditControlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditControl extends EditRecord
{
    protected static string $resource = AuditControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
