<?php

namespace App\Filament\Resources\InternalAuditResource\Widgets;

use App\Filament\Resources\InternalAuditResource\Pages\ListInternalAudits;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class InternalAuditQualificationsChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Internal audit qualifications');
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

        // Obtener auditorías internas con sus calificaciones y paginado
        /* $query = $this->getPageTableQuery()->with('internalAuditQualification')->paginate($pagination);
        $audits = $query->getCollection(); */
        $query = $this->getPageTableQuery();

        $audits = $query->with('internalAuditQualification')->get();

        // Agrupar por título de clasificación
        $grouped = $audits->groupBy(function ($audit) {
            return $audit->internalAuditQualification->title ?? __('Unrated');
        });

        // Conteo del número de auditorías para cada grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Preparar etiquetas para el gráfico (títulos de calificación)
        $labels = $counts->keys()->toArray();

        // Preparar datos para el gráfico (recuentos)
        $data = $counts->values()->toArray();

        // Preparar colores para cada calificación
        $colors = $grouped->map(function ($group) {
            $qualification = $group->first()->internalAuditQualification;
            $colorName = $qualification?->color ?? 'gray';

            return config("filament-colors.{$colorName}.rgba", 'rgba(156, 163, 175, 1)');
        })->values()->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
