<?php

namespace App\Filament\Resources\ActionResource\Widgets;

use App\Models\Action;
use App\Models\ActionType;
use App\Models\Status;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ActionStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Action Status Chart';

    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All',
            'improve' => 'Improve',
            'corrective' => 'Corrective',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Action::query()
            ->when($activeFilter !== 'all', function ($query) use ($activeFilter) {
                $actionTypeId = cache()->rememberForever(
                    "action_type_id_{$activeFilter}",
                    fn () => ActionType::where('name', $activeFilter)->value('id')
                );

                return $query->where('action_type_id', $actionTypeId);
            })
            ->select('status_id', DB::raw('count(*) as count'))
            ->groupBy('status_id') // Group by status
            ->pluck('count', 'status_id');

        $statuses = Status::whereIn('id', $data->keys())->get();

        $chartData = $statuses->map(fn (Status $status) => $data[$status->id]);
        $chartLabels = $statuses->pluck('label');
        $chartColors = $statuses->map(function (Status $status) {
            // Assumes a colorName() method on Status model that returns 'primary', 'success', etc.
            $colorName = $status->colorName();

            return config("filament-colors.{$colorName}.rgba", config('filament-colors.secondary.rgba'));
        });

        return [
            'datasets' => [
                [
                    'label' => 'Action Statuses',
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
