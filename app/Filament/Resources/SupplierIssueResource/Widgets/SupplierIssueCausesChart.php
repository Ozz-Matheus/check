<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Filament\Resources\SupplierIssueResource\Pages\ListSupplierIssues;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class SupplierIssueCausesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Supplier issues by cause');
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
            ->join('supplier_issue_causes', 'supplier_issues.cause_id', '=', 'supplier_issue_causes.id')
            ->selectRaw('supplier_issue_causes.title, count(supplier_issues.id) as count')
            ->groupBy('supplier_issue_causes.title')
            ->pluck('count', 'title');

        return [
            'datasets' => [
                [
                    'label' => __('Supplier issue'),
                    'data' => $data->values()->toArray(),
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
