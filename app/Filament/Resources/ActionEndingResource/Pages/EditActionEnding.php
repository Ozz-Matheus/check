<?php

namespace App\Filament\Resources\ActionEndingResource\Pages;

use App\Filament\Resources\ActionEndingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActionEnding extends EditRecord
{
    protected static string $resource = ActionEndingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
