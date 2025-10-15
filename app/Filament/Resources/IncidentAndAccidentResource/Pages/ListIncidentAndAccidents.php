<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Pages;

use App\Filament\Resources\IncidentAndAccidentResource;
use App\Filament\Resources\IncidentAndAccidentResource\Widgets\IAndAStatsOverview;
use App\Filament\Resources\IncidentAndAccidentResource\Widgets\NewIncidentsAndAccidentsChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncidentAndAccidents extends ListRecords
{
    protected static string $resource = IncidentAndAccidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IAndAStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            NewIncidentsAndAccidentsChart::class,
        ];
    }
}
