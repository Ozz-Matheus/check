<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Pages;

use App\Filament\Resources\IncidentAndAccidentResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewIncidentAndAccident extends ViewRecord
{
    protected static string $resource = IncidentAndAccidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
