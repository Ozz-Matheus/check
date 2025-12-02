<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use App\Models\RiskImpact;
use App\Models\RiskLevel;
use App\Models\RiskProbability;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Widget;

class RiskHeatmapGrid extends Widget
{
    use InteractsWithPageTable;

    protected static string $view = 'filament.widgets.risk-heatmap-grid';

    protected static ?string $pollingInterval = null;

    // protected int|string|array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ListRisks::class;
    }

    public function getViewData(): array
    {
        $impacts = RiskImpact::orderBy('id')->get();
        $probs = RiskProbability::orderBy('id', 'desc')->get();

        $palette = [
            'bajo' => config('filament-colors.success.hex'),   // verde
            'medio' => config('filament-colors.yellow.hex'),  // amarillo
            'alto' => config('filament-colors.warning.hex'),  // naranja
            'muy alto' => config('filament-colors.danger.hex'),    // rojo
            'default' => config('filament-colors.primary.hex'),    // fallback sin acento
        ];

        $cells = [];

        $baseQuery = $this->getPageTableQuery();

        foreach ($probs as $prob) {
            foreach ($impacts as $impact) {
                $query = (clone $baseQuery)
                    ->where('inherent_impact_id', $impact->id)
                    ->where('inherent_probability_id', $prob->id);

                $count = (clone $query)->count();

                $levelTitle = null;

                if ($count > 0) {
                    $risk = (clone $query)->first();
                    $levelId = $risk?->inherent_risk_level_id;

                    $levelTitle = $levelId
                        ? RiskLevel::find($levelId)?->title
                        : null;
                }

                $key = strtolower($levelTitle ?? '');
                $color = $palette[$key] ?? null;

                $cells[$prob->title][$impact->title] = [
                    'count' => $count,
                    'level' => $levelTitle ?? '—',
                    'color' => $color,
                ];
            }
        }

        return [
            'title' => __('Mapa de calor de riesgos inherentes'),
            // 'description' => 'Distribución de riesgos según su probabilidad e impacto residual.',
            'description' => __('Está referenciado a los filtros de la lista'),
            'cells' => $cells,
            'impacts' => $impacts,
            'probs' => $probs,
        ];
    }
}
