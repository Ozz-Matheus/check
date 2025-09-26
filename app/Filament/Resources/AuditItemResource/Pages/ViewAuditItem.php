<?php

namespace App\Filament\Resources\AuditItemResource\Pages;

use App\Filament\Resources\AuditItemResource;
use App\Filament\Resources\InternalAuditResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditItem extends ViewRecord
{
    protected static string $resource = AuditItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /* Action::make('edit')
                ->label(__('Edit'))
                ->url(fn ($record): string => InternalAuditResource::getUrl('audit-item.edit', [
                    'internalAudit' => $record->internal_audit_id,
                    'record' => $record->id,
                ]))
                ->button()
                ->color('primary'), */
            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => InternalAuditResource::getUrl('view', [
                    'record' => $record->internal_audit_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            InternalAuditResource::getUrl('view', ['record' => $this->record->internal_audit_id]) => 'Internal Audit',
            InternalAuditResource::getUrl('audit-item.view', ['internalAudit' => $this->record->internal_audit_id, 'record' => $this->record->id]) => 'Audit Item',
            false => 'View',
        ];
    }
}
