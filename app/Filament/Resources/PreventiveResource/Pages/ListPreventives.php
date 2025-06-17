<?php

namespace App\Filament\Resources\PreventiveResource\Pages;

use App\Filament\Resources\PreventiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreventives extends ListRecords
{
    protected static string $resource = PreventiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
