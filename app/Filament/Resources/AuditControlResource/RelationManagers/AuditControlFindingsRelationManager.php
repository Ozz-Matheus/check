<?php

namespace App\Filament\Resources\AuditControlResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AuditControlFindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('findingType.title'),
                Tables\Columns\TextColumn::make('criteria'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return InternalAuditResource::getUrl('finding.view', [
                    'auditControl' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New finding'))
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('finding_type_id')
                            ->relationship('findingType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('criteria')
                            ->required()
                            ->maxLength(255),
                    ])
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->action(function (array $data) {
                        $this->getOwnerRecord()->findings()->create($data);

                        redirect(InternalAuditResource::getUrl('control.view', [
                            'auditItem' => $this->getOwnerRecord()->audit_item_id,
                            'record' => $this->getOwnerRecord()->id,
                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('View'))
                    ->color('gray')
                    ->icon('heroicon-s-eye')
                    ->url(fn ($record) => InternalAuditResource::getUrl('finding.view', [
                        'auditControl' => $this->getOwnerRecord()->id,
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
