<?php

namespace App\Filament\Resources\AuditItemResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ControlsRelationManager extends RelationManager
{
    protected static string $relationship = 'controls';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),
                Tables\Columns\TextColumn::make('potentialCauses.title')
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->potentialCauses->pluck('title')->join(', ')
                    ),
                Tables\Columns\TextColumn::make('natureOfControl.title'),
                Tables\Columns\TextColumn::make('controlType.title'),
                Tables\Columns\TextColumn::make('controlPeriodicity.title'),
                Tables\Columns\TextColumn::make('effectType.title'),
                Tables\Columns\IconColumn::make('qualified')
                    ->label(__('Qualified'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level.title')
                    ->label(__('Level'))
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return InternalAuditResource::getUrl('control.view', [
                    'auditItem' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New audit control'))
                    ->button()
                    ->color('primary')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn () => InternalAuditResource::getUrl('control.create', [
                        'auditItem' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('View'))
                    ->color('gray')
                    ->icon('heroicon-s-eye')
                    ->url(fn ($record) => InternalAuditResource::getUrl('control.view', [
                        'auditItem' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
