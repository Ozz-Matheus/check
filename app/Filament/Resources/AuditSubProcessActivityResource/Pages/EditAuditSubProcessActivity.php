<?php

namespace App\Filament\Resources\AuditSubProcessActivityResource\Pages;

use App\Filament\Resources\AuditSubProcessActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditSubProcessActivity extends EditRecord
{
    protected static string $resource = AuditSubProcessActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
