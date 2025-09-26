<?php

namespace App\Contexts;

use App\Contracts\ActionOriginInterface;
use App\Models\SupplierIssue;

class SupplierContext implements ActionOriginInterface
{
    public function __construct(protected SupplierIssue $model) {}

    public function getLabel(): string
    {
        return __('Supplier issues');
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
            route('filament.dashboard.resources.supplier-issues.view', [
                'record' => $this->model->id,
            ]) => __('Supplier issue'),
            route('filament.dashboard.resources.supplier-issues.action.create', [
                'model_id' => $this->model->id,
                'model' => class_basename($this->model),
            ]) => __('Action'),
        ];
    }

    public function getRedirectUrl(): string
    {
        return route(
            'filament.dashboard.resources.supplier-issues.view',
            [
                'record' => $this->model->id,
            ]
        );
    }
}
