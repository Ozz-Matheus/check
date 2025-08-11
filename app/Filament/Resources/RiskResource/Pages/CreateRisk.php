<?php

namespace App\Filament\Resources\RiskResource\Pages;

use App\Filament\Resources\RiskPlanResource;
use App\Filament\Resources\RiskResource;
use App\Models\RiskPlan;
use App\Services\RiskService;
use Filament\Resources\Pages\CreateRecord;

class CreateRisk extends CreateRecord
{
    protected static string $resource = RiskResource::class;

    public ?int $risk_plan_id = null;

    public ?RiskPlan $riskPlanModel = null;

    public function mount(): void
    {
        parent::mount();
        $this->risk_plan_id = request()->route('riskPlan');
        $this->riskPlanModel = RiskPlan::findOrFail($this->risk_plan_id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['risk_plan_id'] = $this->risk_plan_id ?? null;

        // dd($data);
        return $data;
    }

    public function updated($property)
    {
        if (in_array($property, ['data.inherent_impact_id', 'data.inherent_probability_id'])) {
            $this->calculateInherentRiskLevel();
        }
    }

    public function calculateInherentRiskLevel()
    {
        $impactId = $this->data['inherent_impact_id'] ?? null;
        $probabilityId = $this->data['inherent_probability_id'] ?? null;

        if ($impactId && $probabilityId) {
            $riskLevel = app(RiskService::class)->riskLevel($impactId, $probabilityId);
            $this->data['inherent_risk_level_id'] = $riskLevel;
            // Parcial para ver el calculo
            $riskLevelCalculated = app(RiskService::class)->riskInherentCalculated($impactId, $probabilityId);
            $this->data['inherent_risk_level_calculated'] = $riskLevelCalculated;
        } else {
            $this->data['inherent_risk_level_id'] = null;
            // Parcial referente al calculo
            $this->data['inherent_risk_level_calculated'] = null;
        }
    }

    protected function getRedirectUrl(): string
    {
        return RiskPlanResource::getUrl('risk.view', [
            'riskPlan' => $this->risk_plan_id,
            'record' => $this->record->id,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
