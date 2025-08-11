<?php

namespace App\Filament\Resources\RiskStrategicContextResource\Pages;

use App\Filament\Resources\RiskStrategicContextResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskStrategicContexts extends ListRecords
{
    protected static string $resource = RiskStrategicContextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
