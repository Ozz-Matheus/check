<?php

namespace App\Filament\Resources\RiskPlanResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskPlan extends EditRecord
{
    protected static string $resource = RiskPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
