<?php

namespace App\Filament\Resources\ActionResource\RelationManagers;

use App\Exports\ActionExports\TaskExport;
use App\Filament\Resources\ActionResource;
use App\Services\ActionService;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class ActionTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

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
                Tables\Columns\TextColumn::make('finished')
                    ->label(__('Finished'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => (bool) $state ? __('Yes') : __('No'))
                    ->color(fn ($state) => (bool) $state ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('Start date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_date')
                    ->label(__('Limit date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_start_date')
                    ->label(__('Real start date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('real_closing_date')
                    ->label(__('Real closing date'))
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
            ->recordUrl(function ($record) {
                return ActionResource::getUrl('task.view', [
                    'action' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New task'))
                    ->color('primary')
                    ->authorize($this->getOwnerRecord()->responsible_by_id === auth()->id() && auth()->user()->can('create_action::task'))
                    ->visible(fn () => app(ActionService::class)->canViewCreateTaskAndFollowUp($this->getOwnerRecord()->status_id))
                    ->url(fn () => ActionResource::getUrl('task.create', [
                        'action' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('follow-up')
                    ->label(__('Follow up'))
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => ActionResource::getUrl('task.view', [
                        'action' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export')
                    ->label(__('Export base'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($records) => Excel::download(
                        new TaskExport($records->pluck('id')->toArray()),
                        'tasks_'.now()->format('Y_m_d_His').'.xlsx'
                    ))
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
