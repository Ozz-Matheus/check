<?php

namespace App\Filament\Resources\AuditImpactResource\Pages;

use App\Filament\Resources\AuditImpactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditImpact extends EditRecord
{
    protected static string $resource = AuditImpactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
