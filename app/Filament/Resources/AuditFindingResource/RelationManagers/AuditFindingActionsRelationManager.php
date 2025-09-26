<?php

namespace App\Filament\Resources\AuditFindingResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use App\Models\AuditFinding;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AuditFindingActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'actions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('type.label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\IconColumn::make('finished')
                    ->label(__('Finished'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ending.real_closing_date')
                    ->label(__('Real Closing Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cancellation_date')
                    ->label(__('Cancellation Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
                Tables\Filters\SelectFilter::make('type_id')
                    ->relationship('type', 'label')
                    ->multiple()
                    ->preload()
                    ->label(__('Type')),
                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'action_and_task')->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->preload()
                    ->label(__('Status')),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New action'))
                    ->button()
                    ->color('primary')
                    ->visible(function () {
                        return str($this->getPageClass())->contains('View');
                    })
                    ->url(function () {

                        $recordId = $this->getOwnerRecord()?->id;
                        $model = class_basename(AuditFinding::class);

                        return InternalAuditResource::getUrl('action.create', [
                            'model_id' => $recordId,
                            'model' => $model,
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
