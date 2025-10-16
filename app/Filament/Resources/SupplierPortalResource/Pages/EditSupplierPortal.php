<?php

namespace App\Filament\Resources\SupplierPortalResource\Pages;

use App\Filament\Resources\SupplierPortalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierPortal extends EditRecord
{
    protected static string $resource = SupplierPortalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
