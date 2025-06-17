<?php

namespace App\Filament\Resources\PreventiveResource\Pages;

use App\Filament\Resources\PreventiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreventive extends EditRecord
{
    protected static string $resource = PreventiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
