<?php

namespace App\Filament\Resources\SupplierIssueResponseResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Filament\Resources\SupplierIssueResponseResource;
use App\Models\Status;
use App\Models\SupplierIssue;
use App\Models\SupplierIssueResponse;
use App\Services\FileService;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierIssueResponse extends CreateRecord
{
    protected static string $resource = SupplierIssueResponseResource::class;

    public ?int $supplier_issue_id = null;

    public ?SupplierIssue $supplierIssueModel = null;

    public function mount(): void
    {
        $this->supplier_issue_id = request()->route('supplier_issue');
        $supplierIssueModel = SupplierIssue::findOrFail($this->supplier_issue_id);
    }

    protected function handleRecordCreation(array $data): SupplierIssueResponse
    {
        $data['supplier_issue_id'] = $this->supplier_issue_id;
        $data['response_date'] = today();

        $supplierResponse = SupplierIssueResponse::create($data);
        $supplierResponse->supplierIssue()->update([
            'status_id' => Status::byContextAndTitle('supplier_issue', 'answered')->id,
        ]);

        return $supplierResponse;
    }

    protected function afterCreate(): void
    {
        app(FileService::class)->createFiles($this->record, $this->form->getState());
    }

    protected function getRedirectUrl(): string
    {
        return SupplierIssueResource::getUrl('response.view', [
            'supplier_issue' => $this->supplier_issue_id,
            'record' => $this->record->id,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
