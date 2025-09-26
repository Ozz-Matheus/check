<?php

namespace App\Filament\Resources\AuditItemResource\Pages;

use App\Filament\Resources\AuditItemResource;
use App\Filament\Resources\InternalAuditResource;
use App\Models\AuditItem;
use App\Models\InternalAudit;
use App\Services\AuditItemService;
use Filament\Resources\Pages\CreateRecord;

class CreateAuditItem extends CreateRecord
{
    protected static string $resource = AuditItemResource::class;

    public ?int $internal_audit_id = null;

    public ?InternalAudit $internalAuditModel = null;

    public function mount(): void
    {
        parent::mount();
        $this->internal_audit_id = request()->route('internalAudit');
        $this->internalAuditModel = InternalAudit::findOrFail($this->internal_audit_id);
    }

    protected function handleRecordCreation(array $data): AuditItem
    {
        $auditItem = AuditItem::create([
            'internal_audit_id' => $this->internal_audit_id,
        ] + $data);

        app(AuditItemService::class)->changeInternalAuditStatusToExecution($auditItem);

        return $auditItem;
    }

    protected function getRedirectUrl(): string
    {
        return InternalAuditResource::getUrl('audit-item.view', [
            'internalAudit' => $this->internal_audit_id,
            'record' => $this->record->id,
        ]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            InternalAuditResource::getUrl('view', ['record' => $this->internal_audit_id]) => 'Internal Audit',
            InternalAuditResource::getUrl('audit-item.create', ['internalAudit' => $this->internal_audit_id]) => 'Audit Item',
            false => 'Create',
        ];
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
