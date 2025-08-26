<?php

namespace App\Filament\Resources\RiskControlQualificationResource\Pages;

use App\Filament\Resources\RiskControlQualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskControlQualifications extends ListRecords
{
    protected static string $resource = RiskControlQualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
