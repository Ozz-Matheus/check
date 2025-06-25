<?php

namespace App\Filament\Resources\AuditResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Models\Status;
use Filament\Resources\Pages\CreateRecord;

class CreateAudit extends CreateRecord
{
    protected static string $resource = AuditResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status_id'] = Status::byContextAndTitle('audit', 'planned')?->id;

        // dd($data);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
