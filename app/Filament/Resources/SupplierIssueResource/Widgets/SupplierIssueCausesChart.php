<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Models\Supplier;
use App\Models\SupplierIssue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SupplierIssueCausesChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'all';

    public function getHeading(): ?string
    {
        return __('Supplier issues by cause');
    }

    protected function getFilters(): ?array
    {
        $topSuppliers = SupplierIssue::query()
            ->select('supplier_id', DB::raw('SUM(monetary_impact) as total_impact'))
            ->whereNotNull('supplier_id')
            ->groupBy('supplier_id')
            ->orderByDesc('total_impact')
            ->limit(5)
            ->pluck('supplier_id');

        $suppliers = Supplier::whereIn('id', $topSuppliers)
            ->pluck('name', 'id')
            ->all();

        return [
            'all' => __('All'),
        ] + $suppliers;
    }

    protected function getData(): array
    {
        $data = SupplierIssue::query()
            ->join('supplier_issue_causes', 'supplier_issues.cause_id', '=', 'supplier_issue_causes.id')
            ->select('supplier_issue_causes.title as cause', DB::raw('count(*) as aggregate'))
            ->groupBy('supplier_issue_causes.title')
            ->orderByDesc('aggregate');

        if ($this->filter !== 'all') {
            $data->where('supplier_issues.supplier_id', $this->filter);
        }

        return [
            'datasets' => [
                [
                    'label' => __('Supplier issue'),
                    'data' => $data->pluck('aggregate')->all(),
                ],
            ],
            'labels' => $data->pluck('cause')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
