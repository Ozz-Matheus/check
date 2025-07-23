<?php

namespace App\Filament\Resources\AuditResource\Pages;

use App\Filament\Resources\AuditResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewAudit extends ViewRecord
{
    protected static string $resource = AuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => AuditResource::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
