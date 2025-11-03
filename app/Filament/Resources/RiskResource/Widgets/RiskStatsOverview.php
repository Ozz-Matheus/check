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

    protected static ?string $pollingInterval = null;

    protected function getColumns(): int
    {
        return 3;
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

        $query = $this->getPageTableQuery()->reorder();

        $riskData = $query
            ->join('risk_impacts as inherent_impact', 'risks.inherent_impact_id', '=', 'inherent_impact.id')
            ->join('risk_probabilities as inherent_prob', 'risks.inherent_probability_id', '=', 'inherent_prob.id')
            ->join('risk_impacts as residual_impact', 'risks.residual_impact_id', '=', 'residual_impact.id')
            ->join('risk_probabilities as residual_prob', 'risks.residual_probability_id', '=', 'residual_prob.id')
            ->selectRaw('
                count(risks.id) as total,
                sum(case when risks.strategic_context_type_id = ? then 1 else 0 end) as internal,
                sum(case when risks.strategic_context_type_id = ? then 1 else 0 end) as external,
                sum(inherent_impact.weight * inherent_prob.weight) as total_inherent_risk,
                sum(residual_impact.weight * residual_prob.weight) as total_residual_risk
            ', [$internalContextId, $externalContextId])
            ->first();

        $internal = $riskData->internal ?? 0;
        $external = $riskData->external ?? 0;

        $totalInherentRisk = $riskData->total_inherent_risk ?? 0;
        $totalResidualRisk = $riskData->total_residual_risk ?? 0;

        $riskReduction = 0;
        if ($totalInherentRisk > 0) {
            $riskReduction = (($totalInherentRisk - $totalResidualRisk) / $totalInherentRisk) * 100;
        }

        return [
            Stat::make(__('Risk count'), $riskData->total ?? 0)
                ->description(__('Total records (variable to filters)'))
                ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Internal and external risks'), "{$internal} - {$external}")
                ->description(__('Total comparisons between types'))
                ->descriptionIcon('heroicon-m-square-2-stack', IconPosition::Before),
            Stat::make(__('Risk reductions'), number_format($riskReduction, 2).'%')
                ->description(__('Average reduction from inherent to residual risk'))
                ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::Before)
                ->color($riskReduction >= 0 ? 'success' : 'danger'),
        ];
    }
}
