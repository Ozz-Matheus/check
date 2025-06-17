<?php

namespace App\Filament\Resources\ImproveResource\Pages;

use App\Filament\Resources\ImproveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImproves extends ListRecords
{
    protected static string $resource = ImproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
