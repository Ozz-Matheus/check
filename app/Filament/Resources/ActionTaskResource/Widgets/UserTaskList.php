<?php

namespace App\Filament\Resources\ActionTaskResource\Widgets;

use App\Filament\Resources\ActionResource;
use App\Models\ActionTask;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UserTaskList extends BaseWidget implements HasTable
{
    protected static ?string $heading = null;

    public function __construct()
    {
        self::$heading = __('My tasks');
    }

    protected function getTableQuery(): ?Builder
    {
        return ActionTask::query()
            ->where('responsible_by_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label(__('Task'))
                ->searchable(),

            Tables\Columns\TextColumn::make('status.label')
                ->searchable()
                ->badge()
                ->color(fn ($record) => $record->status->colorName()),

            Tables\Columns\TextColumn::make('start_date')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('deadline')
                ->date()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('follow-up')
                ->label('Follow-up')
                ->color('primary')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => ActionResource::getUrl('task.view', [
                    'action' => $record->action_id,
                    'record' => $record->id,
                ])),
        ];
    }

    protected function getTableFilters(): array
    {
        return [

            SelectFilter::make('status_id')
                ->label(__('Estado'))
                ->relationship(
                    'status',
                    'label',
                    fn (Builder $query) => $query->where('context', 'task')
                )
                ->multiple()
                ->searchable()
                ->preload(),

        ];

    }
}
