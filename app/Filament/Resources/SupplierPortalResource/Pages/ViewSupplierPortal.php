<?php

namespace App\Filament\Resources\SupplierPortalResource\Pages;

use App\Filament\Resources\SupplierPortalResource;
use App\Models\Status;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplierPortal extends ViewRecord
{
    protected static string $resource = SupplierPortalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label(__('Reply'))
                // 📌 Falta autorización
                ->visible(fn ($record) => $record->status_id === Status::byContextAndTitle('supplier_issue', 'read')->id)
                ->url(fn ($record) => $this->getResource()::getUrl('response.create', [
                    'supplier_issue' => $record->id,
                ]))
                ->color('primary'),
            Action::make('view-answer')
                ->label(__('View answer'))
            // 📌 Falta autorización
                ->visible(fn ($record) => $record->responses()->exists())
                ->url(fn ($record) => $this->getResource()::getUrl('response.view', [
                    'supplier_issue' => $record->id,
                    'record' => $record->responses->id,
                ]))
                ->color('primary'),
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
