<?php

namespace App\Filament\Resources\RiskControlResource\Pages;

use App\Filament\Resources\RiskControlResource;
use App\Filament\Resources\RiskResource;
use App\Models\Risk;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRiskControl extends ViewRecord
{
    protected static string $resource = RiskControlResource::class;

    public ?int $risk_id = null;

    public ?Risk $riskModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);

        $this->risk_id = request()->route('risk');
        $this->riskModel = Risk::findOrFail($this->risk_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => RiskResource::getUrl('view', [
                    'record' => $record->risk_id,
                ]))
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            RiskResource::getUrl('view', ['record' => $this->risk_id]) => __('Risk'),
            RiskResource::getUrl('control.view', ['risk' => $this->risk_id, 'record' => $this->record->id]) => __('Control'),
            false => __('View'),
        ];
    }
}
