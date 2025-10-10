<?php

namespace App\Filament\Resources\AuditControlResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditControlFindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Findings');
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
                Tables\Columns\TextColumn::make('findingType.title')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('criteria')
                    ->label(__('Criteria'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->criteria)
                    ->copyable()
                    ->copyMessage(__('Criteria copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
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
                Tables\Filters\SelectFilter::make('finding_type_id')
                    ->label(__('Type'))
                    ->relationship(
                        name: 'findingType',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New finding'))
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('finding_type_id')
                            ->label(__('Type'))
                            ->relationship(
                                name: 'findingType',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                            )
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('criteria')
                            ->label(__('Criteria'))
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
                Tables\Actions\ViewAction::make()
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
