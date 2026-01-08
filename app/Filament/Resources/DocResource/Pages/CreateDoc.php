<?php

namespace App\Filament\Resources\DocResource\Pages;

use App\Filament\Resources\DocResource;
use App\Notifications\DocCreatedNotice;
use App\Services\DocService;
use App\Support\AppNotifier;
use Filament\Resources\Pages\CreateRecord;

class CreateDoc extends CreateRecord
{
    protected static string $resource = DocResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if (! $user->canAccessSubProcess($data['sub_process_id'] ?? null)) {

            AppNotifier::error(
                __('Access denied'),
                __('You do not have permission to create this file.'),
                true
            );

            $this->halt();
        }

        $data['classification_code'] = app(DocService::class)->generateCode($data['doc_type_id'], $data['sub_process_id'], $data['headquarter_id'] ?? null);
        $data['created_by_id'] = $user->id;

        return $data;
    }

    protected function afterCreate(): void
    {

        auth()->user()->notify(new DocCreatedNotice($this->record));
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
