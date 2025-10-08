<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Widgets;

use App\Models\IncidentAndAccident;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IAndAStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalIAndA = IncidentAndAccident::count();
        $incidents = IncidentAndAccident::where('event_type_id', 1)->count();
        $accidents = IncidentAndAccident::where('event_type_id', 2)->count();

        return [
            Stat::make(__('Total incidents and accidents'), $totalIAndA)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-o-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Incidents'), $incidents)
                ->description(__('Total number of incidents recorded'))
                ->descriptionIcon('heroicon-o-exclamation-circle', IconPosition::Before)
                ->color('warning'),
            Stat::make(__('Accidents'), $accidents)
                ->description(__('Total number of accidents recorded'))
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
