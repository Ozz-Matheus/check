<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;
use Filament\Resources\Pages\CreateRecord;

class CreateAction extends CreateRecord
{
    protected static string $resource = ActionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;

        // dd($data);

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
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
