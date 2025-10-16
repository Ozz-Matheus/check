<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Models\Status;
use App\Services\FileService;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierIssue extends CreateRecord
{
    protected static string $resource = SupplierIssueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status_id'] = Status::byContextAndTitle('supplier_issue', 'open')?->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        app(FileService::class)->createFiles($this->record, $this->form->getState());
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
