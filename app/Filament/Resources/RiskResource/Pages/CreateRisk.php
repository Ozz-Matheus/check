<?php

namespace App\Filament\Resources\RiskResource\Pages;

use App\Filament\Resources\RiskResource;
use App\Models\RiskControlQualification;
use App\Services\RiskService;
use Filament\Resources\Pages\CreateRecord;

class CreateRisk extends CreateRecord
{
    protected static string $resource = RiskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['classification_code'] = app(RiskService::class)->generateCode($data['sub_process_id'], $data['headquarter_id'] ?? null);
        $data['residual_impact_id'] = $data['inherent_impact_id'];
        $data['residual_probability_id'] = $data['inherent_probability_id'];
        $data['residual_risk_level_id'] = $data['inherent_risk_level_id'];
        $data['risk_control_general_qualification_id'] = RiskControlQualification::where('context', 'min')->firstOrFail()->id;

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
            $riskLevel = app(RiskService::class)->inherentLevel($impactId, $probabilityId);
            $this->data['inherent_risk_level_id'] = $riskLevel;
        } else {
            $this->data['inherent_risk_level_id'] = null;
        }
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
