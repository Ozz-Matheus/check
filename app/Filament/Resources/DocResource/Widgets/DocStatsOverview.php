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

        $docsWithDisposition = Doc::whereNotNull('doc_ending_id')->count();

        $docsExpired = Doc::whereDate('central_expiration_date', '<', now())->count();

        $aboutToExpire = Doc::whereDate('central_expiration_date', '>=', now())
            ->whereDate('central_expiration_date', '<=', now()->addDays(30))
            ->count();

        return [
            Stat::make(__('Total Docs'), $totalDocs)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-o-numbered-list', IconPosition::Before),
            Stat::make(__('With final disposition'), $docsWithDisposition)
                ->description($docsWithDisposition >= $totalDocs ? __('Complete') : __('Incomplete'))
                ->descriptionIcon(
                    $docsWithDisposition >= $totalDocs ? 'heroicon-o-check' : 'heroicon-o-exclamation-circle',
                    IconPosition::Before
                )
                ->color($docsWithDisposition >= $totalDocs ? 'success' : 'danger'),
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
