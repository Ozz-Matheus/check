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
            'bajo' => config('filament-colors.success.rgba', 'rgba(34, 197, 94, 0.8)'),   // verde
            'moderado' => config('filament-colors.warning.rgba', 'rgba(251, 191, 36, 0.8)'),  // amarillo
            'alto' => config('filament-colors.primary.rgba', 'rgba(59, 130, 246, 0.8)'),  // azul
            'crítico' => config('filament-colors.danger.rgba', 'rgba(239, 68, 68, 0.8)'),    // rojo
            'critico' => config('filament-colors.danger.rgba', 'rgba(239, 68, 68, 0.8)'),    // fallback sin acento
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
                $color = $palette[$key] ?? '#ffffff'; // blanco

                $cells[$prob->title][$impact->title] = [
                    'count' => $count,
                    'level' => $levelTitle ?? '—',
                    'color' => $color,
                ];
            }
        }

        return compact('cells', 'impacts', 'probs');
    }
}
