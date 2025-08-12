<?php

namespace App\Services;

use App\Contracts\ActionOriginInterface;
use App\Models\RiskTreatment;

/**
 * Servicio para RiskTreatment
 */
class RiskTreatmentService implements ActionOriginInterface
{
    public function __construct(protected RiskTreatment $model) {}

    public function getLabel(): string
    {
        return __('Risks');
    }

    public function getProcessId(): ?int
    {
        return $this->model->risk?->riskPlan?->process_id;
    }

    public function getSubProcessId(): ?int
    {
        return $this->model->risk?->riskPlan?->sub_process_id;
    }

    public function getRedirectUrl(): string
    {
        return route(
            'filament.dashboard.resources.risk-plans.treatment.view',
            [
                'riskPlan' => $this->model->risk?->risk_plan_id,
                'risk' => $this->model->risk_id,
                'record' => $this->model->id,
            ]
        );
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.dashboard.resources.risk-plans.view', [
                'record' => $this->model->risk?->risk_plan_id,
            ]) => __('Risk plan'),

            route('filament.dashboard.resources.risk-plans.risk.view', [
                'riskPlan' => $this->model->risk?->risk_plan_id,
                'record' => $this->model->risk_id,
            ]) => __('Risk'),

            route('filament.dashboard.resources.risk-plans.treatment.view', [
                'riskPlan' => $this->model->risk?->risk_plan_id,
                'risk' => $this->model->risk_id,
                'record' => $this->model->id,
            ]) => __('Treatment'),
        ];
    }
}
