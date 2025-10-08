<?php

namespace App\Filament\Resources\InternalAuditResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'auditItems';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Audit items');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('internal_audit_id')
            ->columns([
                Tables\Columns\TextColumn::make('activity.title')
                    ->label(__('Activity'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->activity->title)
                    ->copyable()
                    ->copyMessage(__('Activity copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('potentialCauses.title')
                    ->label(__('Potential causes'))
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->potentialCauses->pluck('title')->join(', ')
                    ),
                Tables\Columns\TextColumn::make('riskCategory.title')
                    ->label(__('Risk category')),
                Tables\Columns\TextColumn::make('generalLevel.title')
                    ->label(__('General level'))
                    ->placeholder('-'),

            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return InternalAuditResource::getUrl('audit-item.view', [
                    'internalAudit' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                Tables\Filters\SelectFilter::make('riskCategory_id')
                    ->label(__('Risk category'))
                    ->relationship('riskCategory', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('generalLevel_id')
                    ->label(__('General level'))
                    ->relationship(
                        name: 'generalLevel',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'),
                    )
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New audit item'))
                    ->color('primary')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn () => InternalAuditResource::getUrl('audit-item.create', [
                        'internalAudit' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn ($record) => InternalAuditResource::getUrl('audit-item.view', [
                        'internalAudit' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
