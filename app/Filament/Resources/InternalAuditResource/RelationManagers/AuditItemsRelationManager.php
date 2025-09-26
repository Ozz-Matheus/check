<?php

namespace App\Filament\Resources\InternalAuditResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AuditItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'auditItems';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('internal_audit_id')
            ->columns([
                Tables\Columns\TextColumn::make('activity.title'),
                Tables\Columns\TextColumn::make('potentialCauses.title')
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->potentialCauses->pluck('title')->join(', ')
                    ),
                Tables\Columns\TextColumn::make('riskCategory.title'),
                Tables\Columns\TextColumn::make('generalLevel.title'),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return InternalAuditResource::getUrl('audit-item.view', [
                    'internalAudit' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New audit risk'))
                    ->button()
                    ->color('primary')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn () => InternalAuditResource::getUrl('audit-item.create', [
                        'internalAudit' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('View'))
                    ->color('gray')
                    ->icon('heroicon-s-eye')
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
