<?php

namespace App\Filament\Resources\RiskPlanResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRiskPlan extends ViewRecord
{
    protected static string $resource = RiskPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
