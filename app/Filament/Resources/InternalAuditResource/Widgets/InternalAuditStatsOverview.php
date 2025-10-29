<?php

namespace App\Filament\Resources\InternalAuditResource\Widgets;

use App\Filament\Resources\InternalAuditResource\Pages\ListInternalAudits;
use App\Models\InternalAuditQualification;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InternalAuditStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListInternalAudits::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        // Combine count and average into a single, more efficient query.
        $stats = $query
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('avg(qualification_value) as average')
            )
            ->first();

        $color = 'gray';

        if (! is_null($stats->average)) {
            $qualification = InternalAuditQualification::where('min', '<=', $stats->average)
                ->where('max', '>=', $stats->average)
                ->first();

            $color = $qualification?->color ?? 'gray';
        }

        return [
            Stat::make(__('Internal audit count'), $stats->total)
                ->description(__('Total records (variable to filters)'))
                ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Average rating'), number_format($stats->average, 2).'%')
                ->description(__('Average rating of all records (variable to filters)'))
                ->descriptionIcon('heroicon-m-percent-badge', IconPosition::Before)
                ->color($color),
        ];
    }
}
