<?php

namespace App\Filament\Resources\InternalAuditResource\Pages;

use App\Filament\Resources\InternalAuditResource;
use App\Models\Status;
use App\Services\InternalAuditService;
use Filament\Resources\Pages\CreateRecord;

class CreateInternalAudit extends CreateRecord
{
    protected static string $resource = InternalAuditResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['classification_code'] = app(InternalAuditService::class)->generateCode($data['sub_process_id']);
        $data['created_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('internal_audit', 'planned')?->id;

        return $data;
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
