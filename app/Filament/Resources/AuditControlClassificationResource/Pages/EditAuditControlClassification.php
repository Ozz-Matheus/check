<?php

namespace App\Filament\Resources\AuditControlClassificationResource\Pages;

use App\Filament\Resources\AuditControlClassificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditControlClassification extends EditRecord
{
    protected static string $resource = AuditControlClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
