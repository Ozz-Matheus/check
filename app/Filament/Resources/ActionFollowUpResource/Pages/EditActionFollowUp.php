<?php

namespace App\Filament\Resources\ActionFollowUpResource\Pages;

use App\Filament\Resources\ActionFollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActionFollowUp extends EditRecord
{
    protected static string $resource = ActionFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
