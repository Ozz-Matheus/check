<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Models\Risk;
use App\Models\RiskImpact;
use App\Models\RiskLevel;
use App\Models\RiskProbability;
use Filament\Widgets\Widget;

class RiskHeatmapGrid extends Widget
{
    protected static string $view = 'filament.widgets.risk-heatmap-grid';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $impacts = RiskImpact::orderBy('id')->get();
        $probs = RiskProbability::orderBy('id', 'desc')->get();
        $levels = RiskLevel::all(['id', 'title']);

        $palette = [
            'bajo' => config('filament-colors.success.hex'),   // verde
            'medio' => config('filament-colors.yellow.hex'),  // amarillo
            'alto' => config('filament-colors.warning.hex'),  // naranja
            'muy alto' => config('filament-colors.danger.hex'),    // rojo
            'default' => config('filament-colors.primary.hex'),    // fallback sin acento
        ];

        $cells = [];

        foreach ($probs as $prob) {
            foreach ($impacts as $impact) {
                $count = Risk::query()
                    ->where('inherent_impact_id', $impact->id)
                    ->where('inherent_probability_id', $prob->id)
                    ->count();

                $levelTitle = null;

                if ($count > 0) {
                    $levelId = Risk::where('inherent_impact_id', $impact->id)
                        ->where('inherent_probability_id', $prob->id)
                        ->value('inherent_risk_level_id');

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

        $title =  'Mapa de Calor de Riesgos';

        return compact('title', 'cells', 'impacts', 'probs');
    }
}
