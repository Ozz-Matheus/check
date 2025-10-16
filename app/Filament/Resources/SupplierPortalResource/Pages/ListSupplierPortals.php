<?php

namespace App\Filament\Resources\SupplierPortalResource\Pages;

use App\Filament\Resources\SupplierPortalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierPortals extends ListRecords
{
    protected static string $resource = SupplierPortalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
