<?php

namespace App\Filament\Resources\RiskResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use App\Filament\Resources\RiskResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRisk extends ViewRecord
{
    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_treatment')
                ->label(__('Create treatment'))
                ->visible(fn ($record) => ! isset($record->treatment->id))
                ->url(fn ($record): string => RiskPlanResource::getUrl('treatment.create', [
                    'riskPlan' => $record->risk_plan_id,
                    'risk' => $record->id,
                ]))
                ->button()
                ->color('primary'),

            Action::make('view_treatment')
                ->label(__('View treatment'))
                ->visible(fn ($record) => isset($record->treatment->id))
                ->url(fn ($record): string => RiskPlanResource::getUrl('treatment.view', [
                    'riskPlan' => $record->risk_plan_id,
                    'risk' => $record->id,
                    'record' => $record->treatment->id,
                ]))
                ->button()
                ->color('primary'),

            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => RiskPlanResource::getUrl('view', [
                    'record' => $record->risk_plan_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }
}
