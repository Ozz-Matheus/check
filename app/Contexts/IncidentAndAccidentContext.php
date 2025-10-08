<?php

namespace App\Contexts;

use App\Contracts\ActionOriginInterface;
use App\Models\IncidentAndAccident;

class IncidentAndAccidentContext implements ActionOriginInterface
{
    public function __construct(protected IncidentAndAccident $model) {}

    public function getLabel(): string
    {
        return __('Incident And Accidents');
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
            route('filament.dashboard.resources.incident-and-accidents.view', [
                'record' => $this->model->id,
            ]) => __('Incident And Accident'),
            route('filament.dashboard.resources.incident-and-accidents.action.create', [
                'model_id' => $this->model->id,
                'model' => class_basename($this->model),
            ]) => __('Action'),
        ];
    }

    public function getRedirectUrl(): string
    {
        return route(
            'filament.dashboard.resources.incident-and-accidents.view',
            [
                'record' => $this->model->id,
            ]
        );
    }
}
