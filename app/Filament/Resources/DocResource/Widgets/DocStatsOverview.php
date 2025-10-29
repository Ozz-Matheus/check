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
        $query = $this->getPageTableQuery();

        // Al utilizar agregados condicionales, podemos obtener todos los recuentos en una sola consulta.
        $stats = $query->selectRaw('
                count(*) as total,
                count(case when central_expiration_date < now() then 1 end) as expired,
                count(case when central_expiration_date between now() and ? then 1 end) as expiring_soon
            ', [now()->addDays(30)])
            ->reorder() // eliminar cualquier orden predeterminado para el rendimiento
            ->first();

        return [
            Stat::make(__('Document count'), $stats->total)
                ->description(__('Total records (variable to filters)'))
                ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before),
            Stat::make(__('Expiring soon'), $stats->expiring_soon)
                ->description(__('30 days left until expiration'))
                ->descriptionIcon('heroicon-m-clock', IconPosition::Before)
                ->color('warning'),
            Stat::make(__('Expired Documents'), $stats->expired)
                ->description(__('Documents past their expiration date'))
                ->descriptionIcon('heroicon-m-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
