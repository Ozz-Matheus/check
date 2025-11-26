<?php

namespace App\Filament\Resources\ActionResource\Widgets;

use App\Filament\Resources\ActionResource\Pages\ListActions;
use App\Models\Status;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Facades\DB;

class ActionStatusChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListActions::class;
    }

    public function getHeading(): ?string
    {
        return __('Actions by status');
    }

    protected function getData(): array
    {
        // Obtenemos la query de la tabla con TODOS los filtros aplicados
        $query = $this->getPageTableQuery();

        // Clonamos la query y removemos ordenamiento para evitar conflictos con GROUP BY
        $data = (clone $query)
            ->reorder() // Elimina todos los ORDER BY
            ->select('status_id', DB::raw('count(*) as count'))
            ->groupBy('status_id')
            ->pluck('count', 'status_id');

        // Obtenemos los statuses correspondientes
        $statuses = Status::whereIn('id', $data->keys())->get();

        // Preparamos los datos del chart
        $chartData = $statuses->map(fn (Status $status) => $data[$status->id]);
        $chartLabels = $statuses->pluck('label');
        $chartColors = $statuses->map(function (Status $status) {
            $colorName = $status->colorName();

            return config("filament-colors.{$colorName}.rgba", config('filament-colors.darkextra.rgba'));
        });

        return [
            'datasets' => [
                [
                    'data' => $chartData,
                    'backgroundColor' => $chartColors,
                ],
            ],
            'labels' => $chartLabels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
