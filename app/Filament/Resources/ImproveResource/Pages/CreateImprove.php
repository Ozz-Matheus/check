<?php

namespace App\Filament\Resources\ImproveResource\Pages;

use App\Filament\Resources\ImproveResource;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;
use Filament\Resources\Pages\CreateRecord;

class CreateImprove extends CreateRecord
{
    protected static string $resource = ImproveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registration_date'] = now()->toDateString();
        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->responsible_by_id && $this->record->responsibleBy) {
            $this->record->responsibleBy->notify(new ActionCreatedNotice($this->record));
        }
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
