<?php

namespace App\Filament\Resources\ActionSourceResource\Pages;

use App\Filament\Resources\ActionSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActionSources extends ListRecords
{
    protected static string $resource = ActionSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
