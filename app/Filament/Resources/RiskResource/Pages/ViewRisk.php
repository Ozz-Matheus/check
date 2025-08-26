<?php

namespace App\Filament\Resources\RiskResource\Pages;

use App\Filament\Resources\RiskResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRisk extends ViewRecord
{
    protected static string $resource = RiskResource::class;

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
