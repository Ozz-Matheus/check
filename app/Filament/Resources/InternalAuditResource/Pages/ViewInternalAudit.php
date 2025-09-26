<?php

namespace App\Filament\Resources\InternalAuditResource\Pages;

use App\Filament\Resources\InternalAuditResource;
use App\Models\Status;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewInternalAudit extends ViewRecord
{
    protected static string $resource = InternalAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('finish')
                ->label(__('Finish'))
                ->button()
                ->color('success')
                // 📌 Falta la autorización
                ->visible($this->record->qualification_value !== null)
                ->form([
                    Textarea::make('observations')
                        ->label(__('Observations'))
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    $data['status_id'] = Status::byContextAndTitle('internal_audit', 'finished')?->id;
                    $data['evaluated_by_id'] = auth()->id();
                    $this->record->update($data);
                    redirect(InternalAuditResource::getUrl('index'));
                }),
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
