<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Models\SupplierIssue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SupplierIssueCausesChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Supplier issues by cause');
    }

    protected function getData(): array
    {
        $data = SupplierIssue::query()
            ->join('supplier_issue_causes', 'supplier_issues.cause_id', '=', 'supplier_issue_causes.id')
            ->select('supplier_issue_causes.title as cause', DB::raw('count(*) as aggregate'))
            ->groupBy('supplier_issue_causes.title')
            ->orderByDesc('aggregate')
            ->get();

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
