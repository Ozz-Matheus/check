<?php

namespace App\Filament\Resources\InternalAuditResource\Widgets;

use App\Filament\Resources\InternalAuditResource\Pages\ListInternalAudits;
use App\Models\InternalAuditQualification;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

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
        $pagination = $this->getPageTableRecords()->perPage();
        $query = $this->getPageTableQuery()->paginate($pagination);
        $totalInternalAudits = $query->count();
        $averageRating = $query->avg('qualification_value');

        $color = 'gray';

        if (! is_null($averageRating)) {
            $qualification = InternalAuditQualification::where('min', '<=', $averageRating)
                ->where('max', '>=', $averageRating)
                ->first();

            $color = $qualification?->color ?? 'gray';
        }

        return [
            Stat::make(__('Total internal audits'), $totalInternalAudits)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-o-clipboard-document-list', IconPosition::Before),
            /* Stat::make(__('Total internal audits'), $record)
                ->description(__('Records in the system'))
                ->descriptionIcon('heroicon-o-clipboard-document-list', IconPosition::Before), */
            Stat::make(__('Average rating'), number_format($averageRating, 2).'%')
                ->description(__('Average rating of the audits on the list'))
                ->descriptionIcon('heroicon-o-receipt-percent', IconPosition::Before)
                ->color($color),
        ];
    }
}
