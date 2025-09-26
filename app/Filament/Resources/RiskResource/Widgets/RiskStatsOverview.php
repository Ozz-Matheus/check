<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use App\Models\RiskStrategicContextType;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RiskStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListRisks::class;
    }

    protected function getStats(): array
    {
        $internalContextNameId = RiskStrategicContextType::where('name', 'internal')->value('id');
        $externalContextNameId = RiskStrategicContextType::where('name', 'external')->value('id');

        $totalRisks = $this->getPageTableQuery()->count('id');
        $totalInternal = $this->getPageTableQuery()->where('strategic_context_type_id', $internalContextNameId)->count();
        $totalExternal = $this->getPageTableQuery()->where('strategic_context_type_id', $externalContextNameId)->count();

        return [
            Stat::make(__('Total Risks'), $totalRisks)
                ->description(__('General risks in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total Internal Risks'), $totalInternal)
                ->description(__('Internal risks in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total External Risks'), $totalExternal)
                ->description(__('External risks in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
        ];
    }
}
