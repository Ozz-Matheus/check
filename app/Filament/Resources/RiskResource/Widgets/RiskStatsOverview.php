<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use App\Models\RiskStrategicContextType;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class RiskStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListRisks::class;
    }

    protected function getStats(): array
    {
        $contextTypeIds = Cache::remember('risk_context_type_ids', now()->addDay(), function () {
            return RiskStrategicContextType::whereIn('name', ['internal', 'external'])
                ->pluck('id', 'name');
        });

        $internalContextId = $contextTypeIds->get('internal');
        $externalContextId = $contextTypeIds->get('external');

        $query = $this->getPageTableQuery();

        $stats = $query
            ->selectRaw('
                count(id) as total,
                sum(case when strategic_context_type_id = ? then 1 else 0 end) as internal,
                sum(case when strategic_context_type_id = ? then 1 else 0 end) as external
            ', [$internalContextId, $externalContextId])
            ->first();

        $internal = $stats->internal ?? 0;
        $external = $stats->external ?? 0;

        return [
            Stat::make(__('Risk count'), $stats->total ?? 0)
                ->description(__('Total records (variable to filters)'))
                ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Internal and external risks'), "{$internal} - {$external}")
                ->description(__('Total comparisons between types (variable to filters)'))
                ->descriptionIcon('heroicon-m-square-2-stack', IconPosition::Before),
            /* Stat::make(__('Total external risks'), $stats->external ?? 0)
                ->description(__('External risks in the system'))
                ->descriptionIcon('heroicon-m-numbered-list', IconPosition::Before), */
            // Añadir una estadística que muestre el cambio promedio del riesgo inherente al residual es una excelente manera de proporcionar una visión general rápida de la eficacia de la gestión de riesgos.
        ];
    }
}
