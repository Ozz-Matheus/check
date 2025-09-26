<?php

namespace App\Filament\Resources\AuditProbabilityResource\Pages;

use App\Filament\Resources\AuditProbabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditProbability extends EditRecord
{
    protected static string $resource = AuditProbabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
