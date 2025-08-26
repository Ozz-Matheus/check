<?php

namespace App\Filament\Resources\RiskControlResource\Pages;

use App\Filament\Resources\RiskControlResource;
use App\Filament\Resources\RiskResource;
use App\Models\Risk;
use App\Models\RiskControlQualification;
use Filament\Resources\Pages\CreateRecord;

class CreateRiskControl extends CreateRecord
{
    protected static string $resource = RiskControlResource::class;

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
        $data['control_qualification_id'] = $data['risk_control_general_qualification_id'] = RiskControlQualification::where('context', 'min')->firstOrFail()->id;

        // dd($data);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return RiskResource::getUrl('control.view', [
            'risk' => $this->risk_id,
            'record' => $this->record->id,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getBreadcrumbs(): array
    {
        return [
            RiskResource::getUrl('view', ['record' => $this->risk_id]) => 'Risk',
            RiskResource::getUrl('control.create', ['risk' => $this->risk_id]) => 'Control',
            false => 'Create',
        ];
    }
}
