<?php

namespace App\Filament\Resources\DocStorageResource\Pages;

use App\Filament\Resources\DocStorageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocStorage extends EditRecord
{
    protected static string $resource = DocStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
