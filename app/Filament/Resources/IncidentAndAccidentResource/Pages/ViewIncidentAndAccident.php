<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Pages;

use App\Filament\Resources\IncidentAndAccidentResource;
use App\Models\Status;
use App\Services\IncidentAndAccidentService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewIncidentAndAccident extends ViewRecord
{
    protected static string $resource = IncidentAndAccidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('finish')
                ->label(__('Finish'))
                ->visible(fn ($record) => app(IncidentAndAccidentService::class)->canViewFinishIncidentAndAccident($record))
                ->color('success')
                ->form([
                    Textarea::make('observations')
                        ->label(__('Observations'))
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    $data['status_id'] = Status::byContextAndTitle('incident_and_accident', 'finished')?->id;
                    $this->record->update($data);
                    redirect($this->getResource()::getUrl('view', [
                        'record' => $this->record->id,
                    ]));
                }),
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
