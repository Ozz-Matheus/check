<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActionTask extends EditRecord
{
    protected static string $resource = ActionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
