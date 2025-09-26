<?php

namespace App\Filament\Resources\SupplierIssueCauseResource\Pages;

use App\Filament\Resources\SupplierIssueCauseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierIssueCauses extends ListRecords
{
    protected static string $resource = SupplierIssueCauseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
