<?php

namespace App\Filament\Resources\DocDispositionResource\Pages;

use App\Filament\Resources\DocDispositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocDisposition extends EditRecord
{
    protected static string $resource = DocDispositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
