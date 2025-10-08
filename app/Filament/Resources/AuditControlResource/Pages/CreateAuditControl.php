<?php

namespace App\Filament\Resources\AuditControlResource\Pages;

use App\Filament\Resources\AuditControlResource;
use App\Filament\Resources\InternalAuditResource;
use App\Models\AuditItem;
use Filament\Resources\Pages\CreateRecord;

class CreateAuditControl extends CreateRecord
{
    protected static string $resource = AuditControlResource::class;

    public ?int $audit_item_id = null;

    public ?AuditItem $auditItemModel = null;

    public function mount(): void
    {
        parent::mount();
        $this->audit_item_id = request()->route('auditItem');
        $this->auditItemModel = AuditItem::findOrFail($this->audit_item_id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['audit_item_id'] = $this->audit_item_id ?? null;

        // dd($data);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return InternalAuditResource::getUrl('control.view', [
            'auditItem' => $this->audit_item_id,
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
            InternalAuditResource::getUrl('view', ['record' => $this->auditItemModel->internal_audit_id]) => __('Internal Audit'),
            InternalAuditResource::getUrl('audit-item.view', ['internalAudit' => $this->auditItemModel->internal_audit_id, 'record' => $this->audit_item_id]) => __('Audit Item'),
            InternalAuditResource::getUrl('control.create', ['auditItem' => $this->audit_item_id]) => __('Audit Control'),
            false => __('Create'),
        ];
    }
}
