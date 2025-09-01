<?php

namespace App\Contexts;

use App\Contracts\ActionOriginInterface;
use App\Models\Risk;

class RiskContext implements ActionOriginInterface
{
    public function __construct(protected Risk $model) {}

    public function getLabel(): string
    {
        return __('Risks');
    }

    public function getProcessId(): ?int
    {
        return $this->model->process_id;
    }

    public function getSubProcessId(): ?int
    {
        return $this->model->sub_process_id;
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.dashboard.resources.risks.view', [
                'record' => $this->model->id,
            ]) => __('Risk'),
            route('filament.dashboard.resources.risks.action.create', [
                'model_id' => $this->model->id,
                'model' => class_basename($this->model),
            ]) => __('Action'),
        ];
    }

    public function getRedirectUrl(): string
    {
        return route('filament.dashboard.resources.risks.view', [
            'record' => $this->model->id,
        ]
        );
    }
}
