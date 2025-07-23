<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Models\Doc;
use Filament\Widgets\ChartWidget;

class DocStatusesChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function __construct()
    {
        self::$heading = __('doc_statuses_chart');
    }

    protected function getData(): array
    {
        $statusColors = [
            'approved' => config('filament-colors.success.rgba'),
            'rejected' => config('filament-colors.danger.rgba'),
            'pending' => config('filament-colors.primary.rgba'),
            'draft' => config('filament-colors.warning.rgba'),
            'Sin estado' => config('filament-colors.secondary.rgba'),
        ];

        // Obtener todos los registros con su último archivo y estado
        $records = Doc::with('latestVersion.status')->get();

        // Agrupar por el campo "title" del status
        $grouped = $records->groupBy(function ($record) {
            return $record->latestVersion?->status?->title ?? 'Sin estado';
        });

        // Contar registros por grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Obtener los labels visibles para el gráfico (status->label o default capitalizado)
        $labels = $grouped->map(function ($group, $title) {
            return $group->first()->latestVersion?->status?->label ?? ucfirst($title);
        });

        // Obtener colores desde el array manual
        $colors = $grouped->keys()->map(function ($title) use ($statusColors) {
            return $statusColors[$title] ?? '#999999';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Document Statuses',
                    'data' => $counts->values()->toArray(),
                    'backgroundColor' => $colors->values()->toArray(),
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
