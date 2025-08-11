<?php

namespace App\Filament\Resources\RiskProbabilityResource\Pages;

use App\Filament\Resources\RiskProbabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskProbabilities extends ListRecords
{
    protected static string $resource = RiskProbabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
