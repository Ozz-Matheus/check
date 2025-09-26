<?php

namespace App\Filament\Resources\IAndAEventTypeResource\Pages;

use App\Filament\Resources\IAndAEventTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIAndAEventType extends EditRecord
{
    protected static string $resource = IAndAEventTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
