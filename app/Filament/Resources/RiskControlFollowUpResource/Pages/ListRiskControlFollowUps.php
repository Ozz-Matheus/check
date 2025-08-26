<?php

namespace App\Filament\Resources\RiskControlFollowUpResource\Pages;

use App\Filament\Resources\RiskControlFollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiskControlFollowUps extends ListRecords
{
    protected static string $resource = RiskControlFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
