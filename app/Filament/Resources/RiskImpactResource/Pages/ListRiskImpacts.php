<?php

namespace App\Filament\Resources\RiskImpactResource\Pages;

use App\Filament\Resources\RiskImpactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskImpacts extends ListRecords
{
    protected static string $resource = RiskImpactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
