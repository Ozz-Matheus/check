<?php

namespace App\Filament\Resources\RiskResource\RelationManagers;

use App\Filament\Resources\RiskResource;
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
                Tables\Columns\TextColumn::make('potentialCauses.title')
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->potentialCauses->pluck('title')->join(', ')
                    ),
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),
                Tables\Columns\TextColumn::make('periodicity.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('controlType.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('controlQualification.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return RiskResource::getUrl('control.view', [
                    'risk' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New control'))
                    ->button()
                    ->color('primary')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn () => RiskResource::getUrl('control.create', [
                        'risk' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->color('gray')
                    ->icon('heroicon-s-eye')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn ($record) => RiskResource::getUrl('control.view', [
                        'risk' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
