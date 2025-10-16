<?php

namespace App\Filament\Resources\SupplierIssueResponseResource\Pages;

use App\Filament\Resources\SupplierIssueResponseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierIssueResponses extends ListRecords
{
    protected static string $resource = SupplierIssueResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
