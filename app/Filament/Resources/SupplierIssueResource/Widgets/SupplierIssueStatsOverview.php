<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Filament\Resources\SupplierIssueResource\Pages\ListSupplierIssues;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SupplierIssueStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListSupplierIssues::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $stats = $query
            ->selectRaw('
                count(id) as total_issues,
                sum(monetary_impact) as total_monetary_impact
            ')
            ->first();

        $totalSuppliersIssues = $stats->total_issues ?? 0;
        $totalMonetaryImpact = $stats->total_monetary_impact ?? 0;

        return [
            Stat::make(__('Total supplier issues'), $totalSuppliersIssues)
                ->description(__('Total records (variable to filters)'))
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
            Stat::make(__('Monetary impact'), Number::currency($totalMonetaryImpact, 'USD'))
                ->description(__('Total monetary impact of issues'))
                ->descriptionIcon('heroicon-m-banknotes', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
