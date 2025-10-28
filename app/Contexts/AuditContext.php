<?php

namespace App\Contexts;

use App\Contracts\ActionOriginInterface;
use App\Models\AuditFinding;

class AuditContext implements ActionOriginInterface
{
    public function __construct(protected AuditFinding $model) {}

    public function originClassificationCode(): string
    {
        return $this->model->auditControl->auditItem->internalAudit->classification_code;
    }

    public function getLabel(): string
    {
        return __('Internal Audits');
    }

    public function getProcessId(): ?int
    {
        return $this->model->auditControl->auditItem->internalAudit->process_id;
    }

    public function getSubProcessId(): ?int
    {
        return $this->model->auditControl->auditItem->internalAudit->sub_process_id;
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.dashboard.resources.internal-audits.finding.view', [
                'auditControl' => $this->model->audit_control_id,
                'record' => $this->model->id,
            ]) => __('Internal Audit'),
            route('filament.dashboard.resources.internal-audits.action.create', [
                'model_id' => $this->model->id,
                'model' => class_basename($this->model),
            ]) => __('Action'),
        ];
    }

    public function getRedirectUrl(): string
    {
        return route('filament.dashboard.resources.internal-audits.finding.view', [
            'auditControl' => $this->model->audit_control_id,
            'record' => $this->model->id,
        ]
        );
    }
}
