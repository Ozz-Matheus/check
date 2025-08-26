<?php

namespace App\Filament\Resources\RiskControlPeriodicityResource\Pages;

use App\Filament\Resources\RiskControlPeriodicityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskControlPeriodicity extends EditRecord
{
    protected static string $resource = RiskControlPeriodicityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
