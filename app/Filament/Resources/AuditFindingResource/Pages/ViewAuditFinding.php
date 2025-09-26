<?php

namespace App\Filament\Resources\AuditFindingResource\Pages;

use App\Filament\Resources\AuditFindingResource;
use App\Filament\Resources\InternalAuditResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditFinding extends ViewRecord
{
    protected static string $resource = AuditFindingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => InternalAuditResource::getUrl('control.view', [
                    'auditItem' => $record->auditControl->audit_item_id,
                    'record' => $record->audit_control_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            InternalAuditResource::getUrl('view', ['record' => $this->record->auditControl->auditItem->internal_audit_id]) => 'Internal Audit',
            InternalAuditResource::getUrl('audit-item.view', ['internalAudit' => $this->record->auditControl->auditItem->internal_audit_id, 'record' => $this->record->auditControl->audit_item_id]) => 'Audit Item',
            InternalAuditResource::getUrl('control.view', ['auditItem' => $this->record->auditControl->audit_item_id, 'record' => $this->record->audit_control_id]) => 'Audit control',
            InternalAuditResource::getUrl('finding.view', ['auditControl' => $this->record->audit_control_id, 'record' => $this->record->id]) => 'Control finding',
            false => 'View',
        ];
    }
}
