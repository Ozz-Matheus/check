<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Models\DocVersion;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class NewDocumentVersionsChart extends ChartWidget
{
    protected static ?string $heading = 'New Document Versions';

    protected static ?string $maxHeight = '300px';

    protected string|int|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'year';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last 7 days',
            'month' => 'Last 30 days',
            'year' => 'Last year',
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

        $dbData = DocVersion::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                $dateColumn,
                DB::raw('count(*) as aggregate'),
            ])->keyBy('date');

        $period = CarbonPeriod::create($startDate, '1 '.$periodUnit, $endDate);

        $data = collect($period)->mapWithKeys(function ($date) use ($dbData, $periodFormat, $periodUnit) {
            $formattedDate = $date->format($periodUnit === 'day' ? 'Y-m-d' : 'Y-m');
            $label = $date->format($periodFormat);

            return [$label => $dbData->get($formattedDate)?->aggregate ?? 0];
        });

        return [
            'datasets' => [
                [
                    'label' => 'New Versions',
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
