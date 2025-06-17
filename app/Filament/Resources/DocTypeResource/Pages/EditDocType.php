<?php

namespace App\Filament\Resources\DocTypeResource\Pages;

use App\Filament\Resources\DocTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocType extends EditRecord
{
    protected static string $resource = DocTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(function () {
                    return auth()->user()->hasRole('super_admin');
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
