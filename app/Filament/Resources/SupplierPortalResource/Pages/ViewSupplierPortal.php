<?php

namespace App\Filament\Resources\SupplierPortalResource\Pages;

use App\Filament\Resources\SupplierPortalResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplierPortal extends ViewRecord
{
    protected static string $resource = SupplierPortalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('answer')
                ->label(__('Answer'))
                ->url($this->getResource()::getUrl('response.create', [
                    'record' => $this->getRecord()->id,
                ]))
                ->color('primary'),
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
