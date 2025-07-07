<?php

namespace App\Filament\Resources\FindingResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Filament\Resources\FindingResource;
use App\Models\Finding;
use App\Models\Status;
use App\Services\AuditStatusService;
use App\Traits\HasControlContext;
use Filament\Resources\Pages\CreateRecord;

class CreateFinding extends CreateRecord
{
    use HasControlContext;

    protected static string $resource = FindingResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->loadControlContext();
    }

    protected function handleRecordCreation(array $data): Finding
    {

        $finding = Finding::create([
            'control_id' => $this->control_id,
            'title' => $data['title'],
            'audited_sub_process_id' => $data['audited_sub_process_id'],
            'type_of_finding' => $data['type_of_finding'],
            'description' => $data['description'],
            'criteria_not_met' => $data['criteria_not_met'],
            'responsible_auditor_id' => $data['responsible_auditor_id'],
            'status_id' => Status::byContextAndTitle('finding', 'open')?->id,
        ]);

        // app(AuditStatusService::class)->statusChangesInAudits($this->AuditModel, 'in_execution');

        return $finding;
    }

    protected function getRedirectUrl(): string
    {
        return AuditResource::getUrl('audit_control.view', [
            'audit' => $this->ControlModel->audit_id,
            'record' => $this->control_id]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    /* public function getSubheading(): ?string
    {
        return $this->AuditModel?->audit_code;
    } */

    /* public function getBreadcrumbs(): array
    {
        return [
            AuditResource::getUrl('view', ['record' => $this->control_id]) => 'Audit',
            AuditResource::getUrl('audit_finding.create', ['auditId' => $this->control_id]) => 'Finding',
            false => 'Create',
        ];
    } */
}
