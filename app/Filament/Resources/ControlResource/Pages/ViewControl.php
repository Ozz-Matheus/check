<?php

namespace App\Filament\Resources\ControlResource\Pages;

use App\Filament\Resources\AuditResource;
use App\Filament\Resources\ControlResource;
use App\Models\Audit;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewControl extends ViewRecord
{
    protected static string $resource = ControlResource::class;

    public ?int $audit_id = null;

    public ?Audit $auditModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->audit_id = request()->route('audit');
        $this->auditModel = Audit::findOrFail($this->audit_id);
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
                    'action_id' => $record->id,
                    'record' => $record->ending->id,
                ])) */,
            Action::make('back')
                ->label('Return')
                ->url(fn (): string => AuditResource::getUrl('view', ['record' => $this->audit_id]))
                ->button()
                ->color('gray'),
        ];
    }
}
