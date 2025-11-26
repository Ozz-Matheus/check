<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Filament\Resources\DocResource\Pages\ListDocs;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class NewDocumentVersionsChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected string|int|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'week';

    protected function getTablePage(): string
    {
        return ListDocs::class;
    }

    public function getHeading(): ?string
    {
        return __('New document versions');
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => __('Last 7 days'),
            'month' => __('Last 30 days'),
            'year' => __('Last year'),
        ];
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $activeFilter = $this->filter;
        $endDate = now();

        switch ($activeFilter) {
            case 'week':
                $startDate = $endDate->copy()->subDays(6)->startOfDay();
                $periodUnit = 'day';
                $periodFormat = 'M j';
                break;
            case 'month':
                $startDate = $endDate->copy()->subDays(29)->startOfDay();
                $periodUnit = 'day';
                $periodFormat = 'M j';
                break;
            default: // 'year'
                $startDate = $endDate->copy()->subYear()->startOfMonth();
                $periodUnit = 'month';
                $periodFormat = 'M Y';
                break;
        }

        // Obtener las versiones filtradas por la tabla (incluye scope de sede)
        $versions = $query
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Agrupar por fecha
        $groupedData = $versions->groupBy(function ($version) use ($periodUnit) {
            return $periodUnit === 'day'
                ? $version->created_at->format('Y-m-d')
                : $version->created_at->format('Y-m');
        })->map->count();

        // Crear el período completo
        $period = CarbonPeriod::create($startDate, '1 '.$periodUnit, $endDate);

        // Mapear datos con todas las fechas del período
        $data = collect($period)->mapWithKeys(function ($date) use ($groupedData, $periodFormat, $periodUnit) {
            $formattedDate = $date->format($periodUnit === 'day' ? 'Y-m-d' : 'Y-m');
            $label = $date->format($periodFormat);

            return [$label => $groupedData->get($formattedDate, 0)];
        });

        return [
            'datasets' => [
                [
                    'label' => __('New versions'),
                    'data' => $data->values(),
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
