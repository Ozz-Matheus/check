<?php

namespace App\Filament\Resources\DocRecoveryResource\Pages;

use App\Filament\Resources\DocRecoveryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocRecovery extends EditRecord
{
    protected static string $resource = DocRecoveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
