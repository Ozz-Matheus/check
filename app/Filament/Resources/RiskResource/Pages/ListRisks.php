<?php

namespace App\Filament\Resources\RiskResource\Pages;

use App\Filament\Resources\RiskResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ListRecords;
use App\Exports\RiskExecutiveReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class ListRisks extends ListRecords
{
    protected static string $resource = RiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('filtrarPorEstado')
                ->label('Crear acciones')
                ->outlined(),
            Actions\Action::make('filtrarPorEstado')
                ->label('Reporte ejecutivo')
                ->outlined()
                ->modalWidth('md')
                ->form([
                    Select::make('process_id')
                        ->relationship('process', 'title')
                        ->label(__('Process'))
                        ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                        ->searchable()
                        ->preload()
                        ->reactive(),
                    Select::make('sub_process_id')
                        ->relationship(
                            name: 'subProcess',
                            titleAttribute: 'title',
                            modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                        )
                        ->label(__('Sub process'))
                        ->searchable()
                        ->preload(),
                ])
                ->slideOver()
                ->action(function (array $data) {

                    $reportData = RiskExecutiveReportExport::make($data);

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
                    }, 'informe-ejecutivo-riesgos.pdf');

                })->modalSubmitActionLabel('Descargar'),
            Actions\CreateAction::make(),
        ];
    }
}
