<?php

namespace App\Filament\Resources\RiskControlFollowUpResource\Pages;

use App\Filament\Resources\RiskControlFollowUpResource;
use App\Filament\Resources\RiskResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRiskControlFollowUp extends ViewRecord
{
    protected static string $resource = RiskControlFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => RiskResource::getUrl('control.view', [
                    'risk' => $record->control->risk_id,
                    'record' => $record->risk_control_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            RiskResource::getUrl('view', ['record' => $this->record->control->risk_id]) => 'Risk',
            RiskResource::getUrl(
                'control.view',
                [
                    'risk' => $this->record->control->risk_id,
                    'record' => $this->record->risk_control_id,
                ]
            ) => 'Control',
            RiskResource::getUrl(
                'follow-up.view',
                [
                    'control' => $this->record->risk_control_id,
                    'record' => $this->record->id,
                ]
            ) => 'Follow-up',
            false => 'View',
        ];
    }
}
