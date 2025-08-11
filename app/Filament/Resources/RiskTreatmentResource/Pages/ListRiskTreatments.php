<?php

namespace App\Filament\Resources\RiskTreatmentResource\Pages;

use App\Filament\Resources\RiskTreatmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskTreatments extends ListRecords
{
    protected static string $resource = RiskTreatmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
