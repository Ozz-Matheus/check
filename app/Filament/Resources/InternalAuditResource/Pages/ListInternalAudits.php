<?php

namespace App\Filament\Resources\InternalAuditResource\Pages;

use App\Filament\Resources\InternalAuditResource;
use App\Filament\Resources\InternalAuditResource\Widgets\InternalAuditQualificationsChart;
use App\Filament\Resources\InternalAuditResource\Widgets\InternalAuditStatsOverview;
use App\Filament\Resources\InternalAuditResource\Widgets\InternalAuditStatusesChart;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListInternalAudits extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = InternalAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InternalAuditStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            InternalAuditStatusesChart::class,
            InternalAuditQualificationsChart::class,
        ];
    }
}
