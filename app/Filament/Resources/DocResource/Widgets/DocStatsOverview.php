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

        $aboutToExpire = Doc::whereDate('central_expiration_date', '>=', now())
            ->whereDate('central_expiration_date', '<=', now()->addDays(30))
            ->count();

        $docsExpired = Doc::whereDate('central_expiration_date', '<', now())->count();

        return [
            Stat::make(__('Total Docs'), $totalDocs)
                ->description(__('Registers in the system'))
                ->descriptionIcon('heroicon-m-numbered-list', IconPosition::Before),
            Stat::make(__('With final disposition'), $docsWithDisposition)
                ->description($docsWithDisposition >= $totalDocs ? __('Complete') : __('Incomplete'))
                ->descriptionIcon(
                    $docsWithDisposition >= $totalDocs ? 'heroicon-m-check' : 'heroicon-m-exclamation-circle',
                    IconPosition::Before
                )
                ->color($docsWithDisposition >= $totalDocs ? 'success' : 'danger'),
            Stat::make(__('To overdue'), $aboutToExpire)
                ->description(__('30 days left until expiration'))
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning'),
            Stat::make(__('Expired records'), $docsExpired)
                ->description(__('These Registrations have expired'))
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
