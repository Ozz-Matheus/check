<?php

namespace App\Filament\Resources\RiskControlResource\Pages;

use App\Filament\Resources\RiskControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskControls extends ListRecords
{
    protected static string $resource = RiskControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
