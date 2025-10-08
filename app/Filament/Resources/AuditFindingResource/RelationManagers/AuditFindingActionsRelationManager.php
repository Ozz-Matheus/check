<?php

namespace App\Filament\Resources\AuditFindingResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\InternalAuditResource;
use App\Models\AuditFinding;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditFindingActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'actions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Actions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('type.label')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->copyable()
                    ->copyMessage(__('Title copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->label(__('Responsible'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->responsibleBy->name)
                    ->copyable()
                    ->copyMessage(__('Responsible copied'))
                    ->searchable(['name', 'email']),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName())
                    ->icon(fn ($record) => $record->status->iconName())
                    ->placeholder('-'),
                Tables\Columns\IconColumn::make('finished')
                    ->label(__('Finished'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ending.real_closing_date')
                    ->label(__('Real closing date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cancellation_date')
                    ->label(__('Cancellation date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(fn ($record) => ActionResource::getUrl('view', ['record' => $record->id]), true)
            ->filters([
                Tables\Filters\SelectFilter::make('type_id')
                    ->label(__('Type'))
                    ->relationship('type', 'label')
                    ->native(false),
                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'action_and_task')->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->preload(),
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
                Tables\Actions\ViewAction::make()
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn ($record) => ActionResource::getUrl('view', ['record' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
