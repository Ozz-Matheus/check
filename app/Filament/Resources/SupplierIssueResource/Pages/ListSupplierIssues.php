<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierIssues extends ListRecords
{
    protected static string $resource = SupplierIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
