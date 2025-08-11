<?php

namespace App\Filament\Resources\RiskTreatmentResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use App\Filament\Resources\RiskTreatmentResource;
use App\Models\Risk;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskTreatment extends EditRecord
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

    protected function getRedirectUrl(): string
    {
        return RiskPlanResource::getUrl('treatment.view', [
            'riskPlan' => $this->riskModel->risk_plan_id,
            'risk' => $this->risk_id,
            'record' => $this->record->id,
        ]);
    }

    /* protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    } */
}
