<?php

namespace App\Filament\Resources\RiskPlanResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRiskPlan extends CreateRecord
{
    protected static string $resource = RiskPlanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', [
            'record' => $this->record,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
