<?php

namespace App\Filament\Resources\ControlResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Filament\Resources\ControlResource;
use App\Traits\HasAuditContext;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewControl extends ViewRecord
{
    protected static string $resource = ControlResource::class;

    use HasAuditContext;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->loadAuditContext();
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('qualify')
                ->label(__('Qualify'))
                ->button()
                ->color('primary')
            // ->authorize(fn($record) => app(ActionService::class)->canViewActionEnding($record->status_id))
            /* ->url(fn($record) => ActionResource::getUrl('action_endings.view', [
                    'action' => $record->id,
                    'record' => $record->ending->id,
                ])) */,
            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => AuditResource::getUrl('view', ['record' => $this->audit_id]))
                ->button()
                ->color('gray'),
        ];
    }
}
