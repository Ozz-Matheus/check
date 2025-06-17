<?php

namespace App\Filament\Resources\ActionOriginResource\Pages;

use App\Filament\Resources\ActionOriginResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActionOrigins extends ListRecords
{
    protected static string $resource = ActionOriginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
