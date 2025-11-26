<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Filament\Resources\SupplierIssueResource\Pages\ListSupplierIssues;
use App\Models\Supplier;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class GreaterMonetaryImpactOfSuppliersChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected string|int|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'week';

    protected function getTablePage(): string
    {
        return ListSupplierIssues::class;
    }

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

        // Obtener todos los registros filtrados por la tabla (incluye scope de sede)
        $allRecords = $query->get();

        // Calcular los top 5 proveedores por impacto monetario total
        $topSuppliers = $allRecords
            ->groupBy('supplier_id')
            ->map(fn ($issues) => $issues->sum('monetary_impact'))
            ->sortDesc()
            ->take(5);

        $topSupplierIds = $topSuppliers->keys();

        // Obtener nombres de los proveedores
        $suppliers = Supplier::whereIn('id', $topSupplierIds)->pluck('name', 'id');

        // Filtrar registros por proveedores top y rango de fechas
        $filteredRecords = $allRecords
            ->whereIn('supplier_id', $topSupplierIds)
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Crear el período completo
        $period = CarbonPeriod::create($startDate, '1 '.$periodUnit, $endDate);
        $labels = collect($period)->map(fn (Carbon $date) => $date->format($periodFormat));

        $colors = [
            '#FF5733', // A vibrant red-orange
            '#33FF57', // A bright green
            '#3357FF', // A strong blue
            '#FF33A1', // A vivid pink
            '#A133FF', // A rich purple
        ];

        $colorIndex = 0;
        $datasets = $suppliers->map(function ($name, $id) use ($filteredRecords, $period, $periodUnit, $colors, &$colorIndex) {
            // Agrupar por fecha y sumar impacto monetario para este proveedor
            $supplierData = $filteredRecords
                ->where('supplier_id', $id)
                ->groupBy(function ($record) use ($periodUnit) {
                    return $periodUnit === 'day'
                        ? $record->created_at->format('Y-m-d')
                        : $record->created_at->format('Y-m');
                })
                ->map(fn ($records) => $records->sum('monetary_impact'));

            return [
                'label' => $name,
                'data' => collect($period)->map(function (Carbon $date) use ($supplierData, $periodUnit) {
                    $formattedDate = $date->format($periodUnit === 'day' ? 'Y-m-d' : 'Y-m');

                    return $supplierData->get($formattedDate, 0);
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
