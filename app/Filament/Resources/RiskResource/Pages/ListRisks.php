<?php

namespace App\Filament\Resources\RiskResource\Pages;

use App\Exports\RiskExports\RiskReports\RiskReport;
use App\Filament\Resources\RiskResource;
use App\Filament\Resources\RiskResource\Widgets\RiskCategoriesChart;
use App\Filament\Resources\RiskResource\Widgets\RiskContextsChart;
use App\Filament\Resources\RiskResource\Widgets\RiskHeatmapGrid;
use App\Filament\Resources\RiskResource\Widgets\ResidualRiskHeatmapGrid;
use App\Filament\Resources\RiskResource\Widgets\RiskStatsOverview;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListRisks extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('executive_report')
                ->label(__('Executive Report'))
                ->outlined()
                ->modalWidth('md')
                ->form([
                    Select::make('process_id')
                        ->label(__('Process'))
                        ->relationship('process', 'title')
                        ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                        ->searchable()
                        ->preload()
                        ->reactive(),
                    Select::make('sub_process_id')
                        ->label(__('Sub process'))
                        ->relationship(
                            name: 'subProcess',
                            titleAttribute: 'title',
                            modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                        )
                        ->searchable()
                        ->preload(),
                ])
                ->slideOver()
                ->action(function (array $data) {

                    $reportData = RiskReport::make($data);

                    if ($reportData['risks']->isEmpty()) {
                        Notification::make()
                            ->title('Sin datos')
                            ->body('No se encontraron riesgos para esta combinación de proceso y subproceso.')
                            ->warning()
                            ->send();

                        return;
                    }

                    return response()->streamDownload(function () use ($reportData) {
                        echo Pdf::loadView('reports.risk-executive', $reportData)->output();
                    }, __('executive-risk-report.pdf'));

                })->modalSubmitActionLabel(__('Download')),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RiskStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RiskCategoriesChart::class,
            RiskContextsChart::class,
            RiskHeatmapGrid::class,
            ResidualRiskHeatmapGrid::class,
        ];
    }
}
