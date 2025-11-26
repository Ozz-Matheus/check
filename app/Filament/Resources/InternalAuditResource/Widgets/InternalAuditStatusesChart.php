<?php

namespace App\Filament\Resources\InternalAuditResource\Widgets;

use App\Filament\Resources\InternalAuditResource\Pages\ListInternalAudits;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class InternalAuditStatusesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Internal audits by status');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('It is referenced to the list filters');
    }

    protected function getTablePage(): string
    {
        return ListInternalAudits::class;
    }

    protected function getData(): array
    {
        // $pagination = $this->getPageTableRecords()->perPage();

        // Obtener las auditorías internas en la visual de la lista con su estado relacionado y paginado
        /* $query = $this->getPageTableQuery()->with('status')->paginate($pagination);
        $audits = $query->getCollection(); */
        $query = $this->getPageTableQuery();

        $audits = $query->with('status')->get();

        // Agrupar por el campo "title" del status
        $grouped = $audits->groupBy(function ($audit) {
            return $audit->status->title ?? __('Stateless');
        });

        // Contar registros por grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Se obtienen las etiquetas para el gráfico desde la propiedad de etiqueta del estado
        $labels = $grouped->map(function ($group, $title) {
            return $group->first()->status->label ?? ucfirst($title);
        })->values()->toArray();

        // Se obtienen los colores asociados a cada estado y se convierten al formato RGBA para Chart.js
        $colors = $grouped->map(function ($group) {
            $colorName = $group->first()->status->color ?? 'gray';

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
