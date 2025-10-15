<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Widgets;

use App\Models\IncidentAndAccident;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class NewIncidentsAndAccidentsChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    protected string|int|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'week';

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
        $activeFilter = $this->filter;
        $endDate = now();

        switch ($activeFilter) {
            case 'week':
                $startDate = $endDate->copy()->subDays(6)->startOfDay();
                $dateColumn = DB::raw('DATE(created_at) as date');
                $periodUnit = 'day';
                $periodFormat = 'M j';
                break;
            case 'month':
                $startDate = $endDate->copy()->subDays(29)->startOfDay();
                $dateColumn = DB::raw('DATE(created_at) as date');
                $periodUnit = 'day';
                $periodFormat = 'M j';
                break;
            default: // 'year'
                $startDate = $endDate->copy()->subYear()->startOfMonth();
                $dateColumn = DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date");
                $periodUnit = 'month';
                $periodFormat = 'M Y';
                break;
        }

        $query = IncidentAndAccident::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'event_type_id')
            ->orderBy('date')
            ->get([
                $dateColumn,
                'event_type_id',
                DB::raw('count(*) as aggregate'),
            ]);

        $incidentsData = $query->where('event_type_id', 1)->keyBy('date');
        $accidentsData = $query->where('event_type_id', 2)->keyBy('date');

        $period = CarbonPeriod::create($startDate, '1 '.$periodUnit, $endDate);

        $labels = [];
        $incidents = [];
        $accidents = [];

        foreach ($period as $date) {
            $formattedDate = $date->format($periodUnit === 'day' ? 'Y-m-d' : 'Y-m');
            $labels[] = $date->format($periodFormat);
            $incidents[] = $incidentsData->get($formattedDate)?->aggregate ?? 0;
            $accidents[] = $accidentsData->get($formattedDate)?->aggregate ?? 0;
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
