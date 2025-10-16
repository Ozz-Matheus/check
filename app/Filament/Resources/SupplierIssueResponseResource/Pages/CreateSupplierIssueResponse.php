<?php

namespace App\Filament\Resources\SupplierIssueResponseResource\Pages;

use App\Filament\Resources\SupplierIssueResponseResource;
use App\Models\SupplierIssue;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierIssueResponse extends CreateRecord
{
    protected static string $resource = SupplierIssueResponseResource::class;

    public ?int $supplierIssueId = null;

    public function mount(): void
    {

        $supplierIssue = SupplierIssue::findOrFail(request()->route('supplier_issue'));

        $this->supplierIssueId = $supplierIssue->id;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['s_i_id'] = $this->supplierIssueId;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
