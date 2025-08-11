<?php

namespace App\Filament\Resources\RiskLevelResource\Pages;

use App\Filament\Resources\RiskLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskLevels extends ListRecords
{
    protected static string $resource = RiskLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
