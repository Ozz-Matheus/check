<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Models\Doc;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DocStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalDocs = Doc::count();

        $docsExpired = Doc::whereDate('central_expiration_date', '<', now())->count();

        $aboutToExpire = Doc::whereDate('central_expiration_date', '>=', now())
            ->whereDate('central_expiration_date', '<=', now()->addDays(30))
            ->count();

        return [
            Stat::make(__('Total Docs'), $totalDocs)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-o-document-text', IconPosition::Before),
            Stat::make(__('Expiring soon'), $aboutToExpire)
                ->description(__('30 days left until expiration'))
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning'),
            Stat::make(__('Expired Documents'), $docsExpired)
                ->description(__('Documents past their expiration date'))
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
