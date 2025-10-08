<?php

namespace App\Filament\Resources\ActionResource\Widgets;

use App\Models\Action;
use App\Models\ActionType;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActionStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $improveContextNameId = ActionType::where('name', 'improve')->value('id');
        $correctiveContextNameId = ActionType::where('name', 'corrective')->value('id');

        $totalActions = Action::count('id');
        $totalImprove = Action::where('action_type_id', $improveContextNameId)->count();
        $totalCorrective = Action::where('action_type_id', $correctiveContextNameId)->count();
        $totalFinishedActions = Action::where('finished', true)->count();
        $totalUnfinishedActions = Action::where('finished', false)->count();

        return [
            Stat::make(__('Total actions'), $totalActions)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total improves'), $totalImprove)
                ->description(__('Improve actions in the system'))
                ->descriptionIcon('heroicon-s-list-bullet', IconPosition::Before),
            Stat::make(__('Total correctives'), $totalCorrective)
                ->description(__('Corrective actions in the system'))
                ->descriptionIcon('heroicon-s-list-bullet', IconPosition::Before),
            Stat::make(__('Finished actions'), "{$totalFinishedActions} / {$totalUnfinishedActions}")
                ->description(__('Finished vs unfinished'))
                ->descriptionIcon('heroicon-s-check', IconPosition::Before)
                ->chart([$totalFinishedActions, $totalUnfinishedActions])
                ->color('success'),
        ];
    }
}
