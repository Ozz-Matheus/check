<?php

namespace App\Filament\Resources\FindingResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Filament\Resources\FindingResource;
use App\Traits\HasControlContext;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewFinding extends ViewRecord
{
    use HasControlContext;

    protected static string $resource = FindingResource::class;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->loadControlContext();
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('back')
                ->label('Return')
                ->url(fn (): string => AuditResource::getUrl('audit_control.view', ['audit' => $this->ControlModel->audit_id, 'record' => $this->control_id]))
                ->button()
                ->color('gray'),
        ];
    }
}
