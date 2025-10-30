<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Filament\Resources\SupplierIssueResource\Pages\ListSupplierIssues;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class SupplierIssueMonetaryImpactChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Top 5 suppliers by monetary impact');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('It is referenced to the list filters');
    }

    protected function getTablePage(): string
    {
        return ListSupplierIssues::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $data = $query->reorder()
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
