<?php

namespace App\Filament\Resources\DocResource\Pages;

use App\Filament\Resources\DocResource;
use App\Filament\Resources\DocResource\Widgets\DocStatsOverview;
use App\Filament\Resources\DocResource\Widgets\DocStatusesChart;
use App\Filament\Resources\DocResource\Widgets\DocTypesChart;
use App\Filament\Resources\DocResource\Widgets\NewDocumentVersionsChart;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListDocs extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = DocResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DocStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            DocStatusesChart::class,
            DocTypesChart::class,
            NewDocumentVersionsChart::class,
        ];
    }
}
