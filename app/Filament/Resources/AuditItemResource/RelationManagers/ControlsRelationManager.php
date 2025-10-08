<?php

namespace App\Filament\Resources\AuditItemResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
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
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->copyable()
                    ->copyMessage(__('Title copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('potentialCauses.title')
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->potentialCauses->pluck('title')->join(', ')
                    ),
                Tables\Columns\TextColumn::make('natureOfControl.title')
                    ->label(__('Nature of control')),
                Tables\Columns\TextColumn::make('controlType.title')
                    ->label(__('Control type')),
                Tables\Columns\TextColumn::make('controlPeriodicity.title')
                    ->label(__('Control periodicity')),
                Tables\Columns\TextColumn::make('effectType.title')
                    ->label(__('Effect type')),
                Tables\Columns\IconColumn::make('qualified')
                    ->label(__('Qualified'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level.title')
                    ->label(__('Level'))
                    ->placeholder('-'),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return InternalAuditResource::getUrl('control.view', [
                    'auditItem' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                Tables\Filters\SelectFilter::make('nature_of_control_id')
                    ->label(__('Nature of control'))
                    ->relationship('natureOfControl', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('control_type_id')
                    ->label(__('Control type'))
                    ->relationship('controlType', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('control_periodicity_id')
                    ->label(__('Control periodicity'))
                    ->relationship('controlPeriodicity', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('effect_type_id')
                    ->label(__('Effect type'))
                    ->relationship('effectType', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('level_id')
                    ->label(__('Level'))
                    ->relationship(
                        name: 'level',
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
