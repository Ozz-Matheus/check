<?php

namespace App\Filament\Resources\DocExpirationResource\Pages;

use App\Filament\Resources\DocExpirationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocExpiration extends EditRecord
{
    protected static string $resource = DocExpirationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
