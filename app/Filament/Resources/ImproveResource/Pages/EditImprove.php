<?php

namespace App\Filament\Resources\ImproveResource\Pages;

use App\Filament\Resources\ImproveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprove extends EditRecord
{
    protected static string $resource = ImproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
