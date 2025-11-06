<?php

namespace App\Filament\Resources\HeadquarterResource\Pages;

use App\Filament\Resources\HeadquarterResource;
use App\Support\AppNotifier;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHeadquarter extends EditRecord
{
    protected static string $resource = HeadquarterResource::class;

    protected function getHeaderActions(): array
    {
        return [

            // Acción informativa cuando NO se puede borrar
            Actions\Action::make('cannotDeleteInfo')

                ->label(__('Headquarter'))
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->visible(fn ($record) => $record->users()->exists())
                ->tooltip(__('Reassign users to another headquarter before deleting this site.'))
                ->action(function () {

                    AppNotifier::warning(
                        __('Headquarter'),
                        __('Reassign users to another headquarter before deleting this site.')
                    );

                    $this->halt();
                }),

            // Acción real de borrado, solo visible cuando sí se puede
            Actions\DeleteAction::make()
                ->visible(fn ($record) => ! $record->users()->exists()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
