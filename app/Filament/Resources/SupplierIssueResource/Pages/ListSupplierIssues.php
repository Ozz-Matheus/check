<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Filament\Resources\SupplierIssueResource\Widgets\GreaterMonetaryImpactOfSuppliersChart;
use App\Filament\Resources\SupplierIssueResource\Widgets\SupplierIssueCausesChart;
use App\Filament\Resources\SupplierIssueResource\Widgets\SupplierIssueMonetaryImpactChart;
use App\Filament\Resources\SupplierIssueResource\Widgets\SupplierIssueStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierIssues extends ListRecords
{
    protected static string $resource = SupplierIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SupplierIssueStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            SupplierIssueCausesChart::class,
            SupplierIssueMonetaryImpactChart::class,
            GreaterMonetaryImpactOfSuppliersChart::class,
        ];
    }
}
