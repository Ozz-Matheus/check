<?php

namespace App\Filament\Resources\FindingResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Filament\Resources\FindingResource;
use App\Models\Finding;
use App\Models\Status;
use App\Services\AuditStatusService;
use App\Traits\HasAuditContext;
use Filament\Resources\Pages\CreateRecord;

class CreateFinding extends CreateRecord
{
    use HasAuditContext;

    protected static string $resource = FindingResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->loadAuditContext();
    }

    protected function handleRecordCreation(array $data): Finding
    {

        $finding = Finding::create([
            'audit_id' => $this->audit_id,
            'title' => $data['title'],
            'audited_sub_process_id' => $data['audited_sub_process_id'],
            'type_of_finding' => $data['type_of_finding'],
            'description' => $data['description'],
            'criteria_not_met' => $data['criteria_not_met'],
            'responsible_auditor_id' => $data['responsible_auditor_id'],
            'status_id' => Status::byContextAndTitle('finding', 'open')?->id,
        ]);

        app(AuditStatusService::class)->statusChangesInAudits($this->AuditModel, 'in_execution');

        return $finding;
    }

    protected function getRedirectUrl(): string
    {
        return AuditResource::getUrl('view', ['record' => $this->audit_id]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return $this->AuditModel?->audit_code;
    }

    /* public function getBreadcrumbs(): array
    {
        return [
            AuditResource::getUrl('view', ['record' => $this->audit_id]) => 'Audit',
            AuditResource::getUrl('audit_finding.create', ['auditId' => $this->audit_id]) => 'Finding',
            false => 'Create',
        ];
    } */
}
