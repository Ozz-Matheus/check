<?php

namespace App\Filament\Resources\ActionResource\Widgets;

use App\Filament\Resources\ActionResource\Pages\ListActions;
use App\Models\ActionType;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActionStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListActions::class;
    }

    protected function getStats(): array
    {
        $improveContextNameId = ActionType::where('name', 'improve')->value('id');
        $correctiveContextNameId = ActionType::where('name', 'corrective')->value('id');

        $totalActions = $this->getPageTableQuery()->count('id');
        $totalImprove = $this->getPageTableQuery()->where('action_type_id', $improveContextNameId)->count();
        $totalCorrective = $this->getPageTableQuery()->where('action_type_id', $correctiveContextNameId)->count();

        return [
            Stat::make(__('Total Actions'), $totalActions)
                ->description(__('General actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total Improve Actions'), $totalImprove)
                ->description(__('Improve actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total Corrective Actions'), $totalCorrective)
                ->description(__('Corrective actions in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
        ];
    }
}
