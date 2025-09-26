<?php

namespace App\Filament\Resources\DocResource\Pages;

use App\Filament\Resources\DocResource;
use App\Models\Doc;
use App\Notifications\DocCreatedNotice;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDoc extends CreateRecord
{
    protected static string $resource = DocResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        $doc = new Doc($data);

        if (! $user->canAccessSubProcess($data['sub_process_id'] ?? null)) {
            Notification::make()
                ->title(__('Access denied'))
                ->body(__('You do not have permission to create this file.'))
                ->danger()
                ->persistent()
                ->send();
            $this->halt();
        }

        $data['classification_code'] = $doc->docService()->generateCode($data['doc_type_id'], $data['sub_process_id']);
        $data['created_by_id'] = $user->id;

        $docTypeExpiration = $doc->docService()->getDocTypeExpiration($data['doc_type_id']);

        if ($docTypeExpiration) {
            $data['central_expiration_date'] = now()->addYears($docTypeExpiration);
        }

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
