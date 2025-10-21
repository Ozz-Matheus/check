<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Models\Status;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplierIssue extends ViewRecord
{
    protected static string $resource = SupplierIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send')
                ->label(__('Send to supplier'))
                // 📌 Falta la autorización
                ->visible(fn ($record): bool => $record->status_id === Status::byContextAndTitle('supplier_issue', 'open')?->id)
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update([
                        'status_id' => Status::byContextAndTitle('supplier_issue', 'sent')?->id,
                    ]);

                    Notification::make()
                        ->title(__('Sent successfully'))
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),
            Action::make('view-answer')
                ->label(__('View answer'))
            // 📌 Falta autorización
                ->visible(fn ($record) => $record->responses()->exists())
                ->url(fn ($record) => $this->getResource()::getUrl('response.view', [
                    'supplier_issue' => $record->id,
                    'record' => $record->responses?->id,
                ]))
                ->color('primary'),
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
