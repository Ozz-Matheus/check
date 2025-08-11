<?php

namespace App\Filament\Resources\RiskStrategicContextResource\Pages;

use App\Filament\Resources\RiskStrategicContextResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskStrategicContext extends EditRecord
{
    protected static string $resource = RiskStrategicContextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
