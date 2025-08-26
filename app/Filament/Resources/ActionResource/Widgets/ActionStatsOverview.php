<?php

namespace App\Filament\Resources\ActionResource\Widgets;

use App\Models\Action;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActionStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalActions = Action::count('id');
        $totalImprove = Action::where('action_type_id', 1)->count();
        $totalCorrective = Action::where('action_type_id', 2)->count();
        $totalPreventive = Action::where('action_type_id', 3)->count();

        return [
            /* Stat::make(__('Total Actions'), $totalActions)
                ->description(__('Actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before), */
            Stat::make(__('Total Improve Actions'), $totalImprove)
                ->description(__('Improve actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total Preventive Actions'), $totalPreventive)
                ->description(__('Preventive actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total Corrective Actions'), $totalCorrective)
                ->description(__('Corrective actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
        ];
    }
}
