<?php

namespace App\Filament\Resources\ImproveResource\Pages;

use App\Filament\Resources\ImproveResource;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;
use Filament\Resources\Pages\CreateRecord;

class CreateImprove extends CreateRecord
{
    protected static string $resource = ImproveResource::class;

    public ?int $finding_id = null;

    public function mount(): void
    {
        parent::mount();

        $this->finding_id = request()->route('finding');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['finding_id'] = $this->finding_id ?? null;
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
