<?php

namespace App\Filament\Resources\RiskTreatmentResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use App\Filament\Resources\RiskTreatmentResource;
use App\Models\Risk;
use App\Services\RiskService;
use Filament\Resources\Pages\CreateRecord;

class CreateRiskTreatment extends CreateRecord
{
    protected static string $resource = RiskTreatmentResource::class;

    public ?int $risk_id = null;

    public ?Risk $riskModel = null;

    public function mount(): void
    {
        parent::mount();
        $this->risk_id = request()->route('risk');
        $this->riskModel = Risk::findOrFail($this->risk_id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['risk_id'] = $this->risk_id ?? null;

        // dd($data);
        return $data;
    }

    public function updated($property)
    {
        if (in_array($property, ['data.residual_impact_id', 'data.residual_probability_id'])) {
            $this->calculateResidualRiskLevel();
        }
    }

    public function calculateResidualRiskLevel()
    {
        $impactId = $this->data['residual_impact_id'] ?? null;
        $probabilityId = $this->data['residual_probability_id'] ?? null;

        if ($impactId && $probabilityId) {
            $riskLevel = app(RiskService::class)->riskLevel($impactId, $probabilityId);
            $this->data['residual_risk_level_id'] = $riskLevel;
        } else {
            $this->data['residual_risk_level_id'] = null;
        }
    }

    protected function getRedirectUrl(): string
    {
        return RiskPlanResource::getUrl('treatment.view', [
            'riskPlan' => $this->riskModel->risk_plan_id,
            'risk' => $this->risk_id,
            'record' => $this->record->id,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
