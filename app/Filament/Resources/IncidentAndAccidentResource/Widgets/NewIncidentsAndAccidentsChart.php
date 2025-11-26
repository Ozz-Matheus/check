<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Widgets;

use App\Filament\Resources\IncidentAndAccidentResource\Pages\ListIncidentAndAccidents;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class NewIncidentsAndAccidentsChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected string|int|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'week';

    protected function getTablePage(): string
    {
        return ListIncidentAndAccidents::class;
    }

    public function getHeading(): ?string
    {
        return __('New incidents and accidents');
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

        // Obtener los registros filtrados por la tabla (incluye scope de sede)
        $records = $query
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Agrupar incidentes por fecha
        $incidentsData = $records
            ->where('event_type_id', 1)
            ->groupBy(function ($record) use ($periodUnit) {
                return $periodUnit === 'day'
                    ? $record->created_at->format('Y-m-d')
                    : $record->created_at->format('Y-m');
            })
            ->map->count();

        // Agrupar accidentes por fecha
        $accidentsData = $records
            ->where('event_type_id', 2)
            ->groupBy(function ($record) use ($periodUnit) {
                return $periodUnit === 'day'
                    ? $record->created_at->format('Y-m-d')
                    : $record->created_at->format('Y-m');
            })
            ->map->count();

        // Crear el período completo
        $period = CarbonPeriod::create($startDate, '1 '.$periodUnit, $endDate);

        $labels = [];
        $incidents = [];
        $accidents = [];

        foreach ($period as $date) {
            $formattedDate = $date->format($periodUnit === 'day' ? 'Y-m-d' : 'Y-m');
            $labels[] = $date->format($periodFormat);
            $incidents[] = $incidentsData->get($formattedDate, 0);
            $accidents[] = $accidentsData->get($formattedDate, 0);
        }

        return [
            'datasets' => [
                [
                    'label' => __('Incidents'),
                    'data' => $incidents,
                    'borderColor' => config('filament-colors.warning.hex'),
                ],
                [
                    'label' => __('Accidents'),
                    'data' => $accidents,
                    'borderColor' => config('filament-colors.danger.hex'),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
