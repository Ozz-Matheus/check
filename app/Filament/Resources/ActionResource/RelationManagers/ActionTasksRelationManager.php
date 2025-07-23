<?php

namespace App\Filament\Resources\ActionResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\TaskService;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Tasks');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->label(__('Responsible'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('Start date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('Deadline'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_start_date')
                    ->label(__('Actual start date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->label(__('Actual closing date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New task'))
                    ->button()
                    ->color('primary')
                    ->authorize(
                        fn () => app(TaskService::class)->canCreateTask($this->getOwnerRecord()->responsible_by_id, $this->getOwnerRecord()->status_id)
                    )
                    ->url(fn () => ActionResource::getUrl('action_tasks.create', [
                        'action' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('follow-up')
                    ->label(__('Follow-up'))
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => ActionResource::getUrl('action_tasks.view', [
                        'action' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ]);
    }
}
