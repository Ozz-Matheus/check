<?php

namespace App\Filament\Resources\DocResource\Pages;

use App\Filament\Resources\DocResource;
use App\Models\Doc;
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
                'display_restriction' => $data['display_restriction'],
            ]);

            $record->accessToAdditionalUsers()->sync($data['users']);
        }

        // limpiar sesión
        session()->forget('doc_edit_payload');

        $record::notifySuccess(__('Update additional users'));

        redirect()->to(DocResource::getUrl('index'));

    }
}
