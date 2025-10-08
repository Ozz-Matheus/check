<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Models\Risk;
use App\Models\RiskStrategicContextType;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RiskStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $internalContextNameId = RiskStrategicContextType::where('name', 'internal')->value('id');
        $externalContextNameId = RiskStrategicContextType::where('name', 'external')->value('id');

        $totalRisks = Risk::count('id');
        $totalInternal = Risk::where('strategic_context_type_id', $internalContextNameId)->count();
        $totalExternal = Risk::where('strategic_context_type_id', $externalContextNameId)->count();

        return [
            Stat::make(__('Total risks'), $totalRisks)
                ->description(__('General risks in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total internal risks'), $totalInternal)
                ->description(__('Internal risks in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
            Stat::make(__('Total external risks'), $totalExternal)
                ->description(__('External risks in the system'))
                ->descriptionIcon('heroicon-s-numbered-list', IconPosition::Before),
        ];
    }
}
