<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Filament\Resources\DocResource\Pages\ListDocs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class DocStatusesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Documents by status');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('It is referenced to the list filters');
    }

    protected function getTablePage(): string
    {
        return ListDocs::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        // Obtener todos los registros con su último archivo y estado (sin paginar para el widget)
        $records = $query->with('latestVersion.status')->get();

        // Agrupar por el campo "title" del status
        $grouped = $records->groupBy(function ($record) {
            return $record->latestVersion?->status?->title ?? __('Without status');
        });

        // Contar registros por grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Obtener los labels visibles para el gráfico (status->label o default capitalizado)
        $labels = $grouped->map(function ($group, $title) {
            return $group->first()->latestVersion?->status?->label ?? ucfirst($title);
        })->values()->toArray();

        $colors = $grouped->map(function ($group) {
            $colorName = $group->first()->latestVersion?->status?->color ?? 'gray';

            return config("filament-colors.{$colorName}.rgba", 'rgba(156, 163, 175, 1)');
        })->values()->toArray();

        return [
            'datasets' => [
                [
                    'data' => $counts->values()->toArray(),
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
