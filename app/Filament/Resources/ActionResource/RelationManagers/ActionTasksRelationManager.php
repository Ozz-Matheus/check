<?php

namespace App\Filament\Resources\ActionResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\TaskService;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActionTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\IconColumn::make('finished')
                    ->boolean(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('actual_closing_date')
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
            ->recordUrl(function ($record) {
                return ActionResource::getUrl('task.view', [
                    'action' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('New action task')
                    ->button()
                    ->color('primary')
                    ->authorize($this->getOwnerRecord()->responsible_by_id === auth()->id() && auth()->user()->can('create_action::task'))
                    ->visible(fn () => app(TaskService::class)->canViewCreateTask($this->getOwnerRecord()->status_id))
                    ->url(fn () => ActionResource::getUrl('task.create', [
                        'action' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('follow-up')
                    ->label('Follow-up')
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => ActionResource::getUrl('task.view', [
                        'action' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ]);
    }
}
