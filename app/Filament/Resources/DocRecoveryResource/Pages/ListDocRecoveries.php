<?php

namespace App\Filament\Resources\DocRecoveryResource\Pages;

use App\Filament\Resources\DocRecoveryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocRecoveries extends ListRecords
{
    protected static string $resource = DocRecoveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
