<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Models\Supplier;
use App\Models\SupplierIssue;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GreaterMonetaryImpactOfSuppliersChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    protected string|int|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'week';

    public function getHeading(): ?string
    {
        return __('Behavior of the top 5 suppliers with monetary impact');
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

        $topSuppliers = SupplierIssue::query()
            ->select('supplier_id', DB::raw('SUM(monetary_impact) as total_impact'))
            ->groupBy('supplier_id')
            ->orderByDesc('total_impact')
            ->limit(5)
            ->pluck('supplier_id');

        $suppliers = Supplier::whereIn('id', $topSuppliers)->pluck('name', 'id');

        $query = SupplierIssue::query()
            ->whereIn('supplier_id', $topSuppliers)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'supplier_id')
            ->orderBy('date')
            ->get([
                $dateColumn,
                'supplier_id',
                DB::raw('sum(monetary_impact) as aggregate'),
            ]);

        $period = CarbonPeriod::create($startDate, '1 '.$periodUnit, $endDate);
        $labels = collect($period)->map(fn (Carbon $date) => $date->format($periodFormat));

        $colors = [
            '#FF5733', // A vibrant red-orange
            '#33FF57', // A bright green
            '#3357FF', // A strong blue
            '#FF33A1', // A vivid pink
            '#A133FF', // A rich purple
        ];

        $datasets = $suppliers->map(function ($name, $id) use ($query, $period, $periodUnit, $colors) {
            $supplierData = $query->where('supplier_id', $id)->keyBy('date');
            static $colorIndex = 0;

            return [
                'label' => $name,
                'data' => collect($period)->map(function (Carbon $date) use ($supplierData, $periodUnit) {
                    $formattedDate = $date->format($periodUnit === 'day' ? 'Y-m-d' : 'Y-m');

                    return $supplierData->get($formattedDate)?->aggregate ?? 0;
                })->all(),
                'borderColor' => $colors[$colorIndex++ % count($colors)],
            ];
        })->values()->all();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
