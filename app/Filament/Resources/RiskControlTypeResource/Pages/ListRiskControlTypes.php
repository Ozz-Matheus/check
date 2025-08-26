<?php

namespace App\Filament\Resources\RiskControlTypeResource\Pages;

use App\Filament\Resources\RiskControlTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskControlTypes extends ListRecords
{
    protected static string $resource = RiskControlTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
