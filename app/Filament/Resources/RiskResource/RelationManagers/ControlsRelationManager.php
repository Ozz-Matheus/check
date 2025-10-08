<?php

namespace App\Filament\Resources\RiskResource\RelationManagers;

use App\Filament\Resources\RiskResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ControlsRelationManager extends RelationManager
{
    protected static string $relationship = 'controls';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Controls');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('potentialCauses.title')
                    ->label(__('Potential causes'))
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->potentialCauses->pluck('title')->join(', ')
                    )
                    ->copyable()
                    ->copyMessage(__('Potential causes copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->copyable()
                    ->copyMessage(__('Title copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('periodicity.title')
                    ->label(__('Periodicity')),
                Tables\Columns\TextColumn::make('controlType.title')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('controlQualification.title')
                    ->label(__('Qualification')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
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
                Tables\Filters\SelectFilter::make('periodicity_id')
                    ->label(__('Periodicity'))
                    ->relationship('periodicity', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('controlType_id')
                    ->label(__('Type'))
                    ->relationship('controlType', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('controlQualification_id')
                    ->label(__('Qualification'))
                    ->relationship(
                        name: 'controlQualification',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'),
                    )
                    ->native(false),
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
                Tables\Actions\ViewAction::make()
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
