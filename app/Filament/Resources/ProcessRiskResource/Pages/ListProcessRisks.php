<?php

namespace App\Filament\Resources\ProcessRiskResource\Pages;

use App\Filament\Resources\ProcessRiskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessRisks extends ListRecords
{
    protected static string $resource = ProcessRiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
