<?php

namespace App\Filament\Resources\ActionResource\Widgets;

use App\Models\Action;
use App\Models\ActionType;
use Filament\Widgets\ChartWidget;

class ActionStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Action Status Chart';

    public ?string $filter = 'improve';

    protected function getFilters(): ?array
    {
        return [
            'improve' => 'Improve',
            'preventive' => 'Preventive',
            'corrective' => 'Corrective',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $actionTypeId = ActionType::where('name', $activeFilter)->value('id');

        $statusColors = [
            'finished' => 'rgba(22, 163, 74, 1)', // verde
            'canceled' => 'rgba(220, 38, 38, 1)', // rojo
            'in_execution' => 'rgba(79, 70, 229, 1)', // azul
            'proposal' => 'rgba(161, 161, 170, 1)', // amarillo
            'Sin estado' => 'rgba(203, 213, 225, 1)', // gris para registros sin estado
        ];

        // Obtener todos los registros con su último archivo y estado
        $records = Action::with('Status')->where('action_type_id', $actionTypeId)->get();
        // dd($records);

        // Agrupar por el campo "title" del status
        $grouped = $records->groupBy(function ($record) {
            return $record->Status?->title ?? 'Sin estado';
        });

        // Contar registros por grupo
        $counts = $grouped->map(fn ($group) => $group->count());

        // Obtener los labels visibles para el gráfico (status->label o default capitalizado)
        $labels = $grouped->map(function ($group, $title) {
            return $group->first()->Status?->label ?? ucfirst($title);
        });

        // Obtener colores desde el array manual
        $colors = $grouped->keys()->map(function ($title) use ($statusColors) {
            return $statusColors[$title] ?? '#999999';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Action Statuses',
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
