<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ActionResource\Widgets\ActionStatsOverview;
use App\Filament\Resources\ActionResource\Widgets\ActionStatusChart;
use App\Filament\Resources\ActionTaskResource\Widgets\UserTaskList;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListActions extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ActionStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ActionStatusChart::class,
            UserTaskList::class,
        ];
    }
}
