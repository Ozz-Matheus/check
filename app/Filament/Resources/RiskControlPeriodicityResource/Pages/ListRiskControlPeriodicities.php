<?php

namespace App\Filament\Resources\RiskControlPeriodicityResource\Pages;

use App\Filament\Resources\RiskControlPeriodicityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskControlPeriodicities extends ListRecords
{
    protected static string $resource = RiskControlPeriodicityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
