<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Widgets;

use App\Filament\Resources\IncidentAndAccidentResource\Pages\ListIncidentAndAccidents;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IAndAStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListIncidentAndAccidents::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $stats = $query
            ->selectRaw('
                count(id) as total,
                count(case when event_type_id = 1 then 1 end) as incidents,
                count(case when event_type_id = 2 then 1 end) as accidents
            ')
            ->first();

        return [
            Stat::make(__('Total incidents and accidents'), $stats->total ?? 0)
                ->description(__('Total records (variable to filters)'))
                ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Incidents'), $stats->incidents ?? 0)
                ->description(__('Total number of incidents recorded'))
                ->descriptionIcon('heroicon-m-exclamation-circle', IconPosition::Before)
                ->color('warning'),
            Stat::make(__('Accidents'), $stats->accidents ?? 0)
                ->description(__('Total number of accidents recorded'))
                ->descriptionIcon('heroicon-m-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
