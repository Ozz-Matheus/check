<?php

namespace App\Filament\Resources\ControlResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Filament\Resources\ControlResource;
use App\Models\Status;
use App\Traits\HasAuditContext;
use Filament\Resources\Pages\CreateRecord;

class CreateControl extends CreateRecord
{
    protected static string $resource = ControlResource::class;

    use HasAuditContext;

    public function mount(): void
    {

        parent::mount();
        $this->loadAuditContext();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['audit_id'] = $this->audit_id ?? null;
        $data['status_id'] = Status::byContextAndTitle('control', 'unrated')?->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return AuditResource::getUrl('view', ['record' => $this->audit_id]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
