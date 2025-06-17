<?php

namespace App\Filament\Resources\DocEndingResource\Pages;

use App\Filament\Resources\DocEndingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocEnding extends EditRecord
{
    protected static string $resource = DocEndingResource::class;

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
