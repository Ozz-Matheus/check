<?php

namespace App\Filament\Resources\RiskTreatmentResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use App\Filament\Resources\RiskTreatmentResource;
use App\Models\Risk;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRiskTreatment extends ViewRecord
{
    protected static string $resource = RiskTreatmentResource::class;

    public ?int $risk_id = null;

    public ?Risk $riskModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);

        $this->risk_id = request()->route('risk');
        $this->riskModel = Risk::findOrFail($this->risk_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label(__('Edit'))
                ->url(fn (): string => RiskPlanResource::getUrl('treatment.edit', [
                    'riskPlan' => $this->riskModel->risk_plan_id,
                    'risk' => $this->risk_id,
                    'record' => $this->record->id,
                ]))
                ->button()
                ->color('primary'),

            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => RiskPlanResource::getUrl('risk.view', [
                    'riskPlan' => $this->riskModel->risk_plan_id,
                    'record' => $this->risk_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }
}
