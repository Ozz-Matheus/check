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

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListActions::class;
    }

    protected function getStats(): array
    {
        // Obtener la query con los filtros aplicados
        $query = $this->getPageTableQuery();

        $improveContextNameId = ActionType::where('name', 'improve')->value('id');
        $correctiveContextNameId = ActionType::where('name', 'corrective')->value('id');

        // Usar la query filtrada para todos los stats
        $totalActions = (clone $query)->count();
        $totalImprove = (clone $query)->where('action_type_id', $improveContextNameId)->count();
        $totalCorrective = (clone $query)->where('action_type_id', $correctiveContextNameId)->count();
        $totalFinishedActions = (clone $query)->whereHas('ending', function ($q) {
            $q->where('finished', true);
        })->count();

        // Calcular cumplimiento de fechas límite con la query filtrada
        $complianceStats = $this->calculateDateCompliance($query);

        return [
            Stat::make(__('Total actions'), $totalActions)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Corrective and improvement actions'), "{$totalImprove} - {$totalCorrective}")
                ->description(__('Total comparisons between types'))
                ->descriptionIcon('heroicon-s-list-bullet', IconPosition::Before),
            Stat::make(__('Finished actions'), $totalFinishedActions)
                ->description(__('Total of actions finished'))
                ->descriptionIcon('heroicon-s-check', IconPosition::Before)
                ->chart([$totalFinishedActions])
                ->color('success'),
            Stat::make(__('Date compliance'), number_format($complianceStats['percentage'], 2).'%')
                ->description(__('Actions completed on time'))
                ->descriptionIcon('heroicon-s-clock', IconPosition::Before)
                ->color($complianceStats['color']),
        ];
    }

    /**
     * Calcula el cumplimiento de fechas límite usando la query filtrada
     */
    protected function calculateDateCompliance($query): array
    {
        // Obtener acciones que tienen ending con finished=true y tienen limit_date
        // usando la query filtrada de la tabla
        $actions = (clone $query)
            ->whereHas('ending', function ($q) {
                $q->where('finished', true)
                    ->whereNotNull('finished_date');
            })
            ->whereNotNull('limit_date')
            ->with('ending:id,action_id,finished_date')
            ->get(['id', 'limit_date']);

        $total = $actions->count();

        if ($total === 0) {
            return [
                'percentage' => 0,
                'color' => 'gray',
            ];
        }

        // Contar acciones completadas a tiempo (finished_date <= limit_date)
        $onTime = $actions->filter(function ($action) {
            return $action->ending
                && $action->ending->finished_date
                && $action->ending->finished_date <= $action->limit_date;
        })->count();

        $percentage = ($onTime / $total) * 100;

        // Determinar color según el porcentaje
        $color = match (true) {
            $percentage >= 80 => 'success',
            $percentage >= 60 => 'warning',
            default => 'danger',
        };

        return [
            'percentage' => $percentage,
            'color' => $color,
        ];
    }
}
