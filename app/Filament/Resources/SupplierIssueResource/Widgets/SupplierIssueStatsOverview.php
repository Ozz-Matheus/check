<?php

namespace App\Filament\Resources\SupplierIssueResource\Widgets;

use App\Models\SupplierIssue;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SupplierIssueStatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $totalSuppliersIssues = SupplierIssue::query()->count('id');
        $totalMonetaryImpact = SupplierIssue::query()->whereYear('created_at', now()->year)->sum('monetary_impact');

        return [
            Stat::make(__('Total supplier issues'), $totalSuppliersIssues)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
            Stat::make(__('Monetary impact of this year'), Number::currency($totalMonetaryImpact, 'USD'))
                ->description(__('Total monetary impact of issues'))
                ->descriptionIcon('heroicon-m-banknotes', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
