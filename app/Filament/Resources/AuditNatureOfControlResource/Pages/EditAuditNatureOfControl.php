<?php

namespace App\Filament\Resources\AuditNatureOfControlResource\Pages;

use App\Filament\Resources\AuditNatureOfControlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditNatureOfControl extends EditRecord
{
    protected static string $resource = AuditNatureOfControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
