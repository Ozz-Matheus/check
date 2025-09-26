<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Filament\Resources\DocResource\Pages\ListDocs;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DocStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListDocs::class;
    }

    protected function getStats(): array
    {
        $totalDocs = $this->getPageTableQuery()->count();

        $docsExpired = $this->getPageTableQuery()->whereDate('central_expiration_date', '<', now())->count();

        $aboutToExpire = $this->getPageTableQuery()->whereDate('central_expiration_date', '>=', now())
            ->whereDate('central_expiration_date', '<=', now()->addDays(30))
            ->count();

        return [
            Stat::make(__('Total Docs'), $totalDocs)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-o-numbered-list', IconPosition::Before),
            Stat::make(__('To overdue'), $aboutToExpire)
                ->description(__('30 days left until expiration'))
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning'),
            Stat::make(__('Expired registrations'), $docsExpired)
                ->description(__('Expired records'))
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
