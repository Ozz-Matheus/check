<?php

namespace App\Filament\Resources\RiskImpactResource\Pages;

use App\Filament\Resources\RiskImpactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskImpact extends EditRecord
{
    protected static string $resource = RiskImpactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
