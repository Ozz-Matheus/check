<?php

namespace App\Filament\Resources\CorrectiveResource\Pages;

use App\Filament\Resources\CorrectiveResource;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;
use Filament\Resources\Pages\CreateRecord;

class CreateCorrective extends CreateRecord
{
    protected static string $resource = CorrectiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registration_date'] = now()->toDateString();
        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;

        return $data;
    }

    protected function afterCreate(): void
    {

        auth()->user()->notify(new ActionCreatedNotice($this->record));
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
