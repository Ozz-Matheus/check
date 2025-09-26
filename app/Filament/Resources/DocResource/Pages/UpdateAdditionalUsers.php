<?php

namespace App\Filament\Resources\DocResource\Pages;

use App\Filament\Resources\DocResource;
use App\Models\Doc;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class UpdateAdditionalUsers extends Page
{
    protected static string $resource = DocResource::class;

    protected static string $view = 'filament::pages.actions';

    public function mount(Doc $record): void
    {
        $data = session('doc_edit_payload');

        if ($data) {
            $record->update([
                'visibility' => $data['visibility'],
            ]);

            $record->accessToAdditionalUsers()->sync($data['users']);
        }

        // limpiar sesión
        session()->forget('doc_edit_payload');

        Notification::make()
            ->title(__('Update additional users'))
            ->success()
            ->send();

        redirect()->to(DocResource::getUrl('index'));

    }
}
