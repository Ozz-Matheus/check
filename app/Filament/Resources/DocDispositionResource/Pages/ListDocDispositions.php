<?php

namespace App\Filament\Resources\DocDispositionResource\Pages;

use App\Filament\Resources\DocDispositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocDispositions extends ListRecords
{
    protected static string $resource = DocDispositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
