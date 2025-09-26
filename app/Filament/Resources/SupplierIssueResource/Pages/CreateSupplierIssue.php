<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Models\Status;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierIssue extends CreateRecord
{
    protected static string $resource = SupplierIssueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status_id'] = Status::byContextAndTitle('supplier_issue', 'open')?->id;

        // dd($data);
        return $data;
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
