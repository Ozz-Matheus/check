<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierIssue extends EditRecord
{
    protected static string $resource = SupplierIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
