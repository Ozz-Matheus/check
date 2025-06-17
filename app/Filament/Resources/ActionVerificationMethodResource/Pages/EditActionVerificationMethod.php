<?php

namespace App\Filament\Resources\ActionVerificationMethodResource\Pages;

use App\Filament\Resources\ActionVerificationMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActionVerificationMethod extends EditRecord
{
    protected static string $resource = ActionVerificationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
