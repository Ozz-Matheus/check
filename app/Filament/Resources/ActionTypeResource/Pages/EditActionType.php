<?php

namespace App\Filament\Resources\ActionTypeResource\Pages;

use App\Filament\Resources\ActionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActionType extends EditRecord
{
    protected static string $resource = ActionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
