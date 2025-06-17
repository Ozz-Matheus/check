<?php

namespace App\Filament\Resources\DocVersionResource\Pages;

use App\Filament\Resources\DocVersionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocVersion extends EditRecord
{
    protected static string $resource = DocVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
