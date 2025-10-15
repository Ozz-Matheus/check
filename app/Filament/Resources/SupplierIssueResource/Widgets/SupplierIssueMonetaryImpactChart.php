<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Models\SupplierIssue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SupplierIssueMonetaryImpactChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Top 5 suppliers by monetary impact');
    }

    protected function getData(): array
    {
        $data = SupplierIssue::query()
            ->join('users', 'supplier_issues.supplier_id', '=', 'users.id')
            ->select('users.name as supplier', DB::raw('SUM(monetary_impact) as total_impact'))
            ->groupBy('users.name')
            ->orderByDesc('total_impact')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Monetary impact'),
                    'data' => $data->pluck('total_impact')->all(),
                ],
            ],
            'labels' => $data->pluck('supplier')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
