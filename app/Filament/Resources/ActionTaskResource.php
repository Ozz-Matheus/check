<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionTaskResource\Pages;
use App\Filament\Resources\ActionTaskResource\RelationManagers\ActionTaskCommentsRelationManager;
use App\Filament\Resources\ActionTaskResource\RelationManagers\ActionTaskFilesRelationManager;
use App\Models\ActionTask;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ActionTaskResource extends Resource
{
    protected static ?string $model = ActionTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Action Task Data')
                    ->description('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('detail')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('responsible_by_id')
                            ->relationship(
                                'responsibleBy',
                                'name',
                                modifyQueryUsing: fn ($query, $livewire) => $query->whereHas(
                                    'subProcesses',
                                    fn ($q) => $q->where('sub_process_id', $livewire->actionModel->sub_process_id)
                                )
                            )
                            ->label(__('Responsible'))
                            /* ->options(fn ($livewire) => method_exists($livewire, 'getResponsibleUserOptions')
                                ? $livewire->getResponsibleUserOptions()
                                : []) */
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->minDate(now()->format('Y-m-d'))
                            ->maxDate(fn ($livewire) => $livewire->actionModel?->deadline?->toDateString())
                            // ->maxDate(fn($livewire) => method_exists($livewire, 'getMaxStartDate') ? $livewire->getMaxStartDate() : null)
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('deadline', null);
                            })
                            ->reactive()
                            ->native(false)
                            ->required(),
                        Forms\Components\DatePicker::make('deadline')
                            ->minDate(fn (Forms\Get $get) => $get('start_date'))
                            ->maxDate(fn ($livewire) => $livewire->actionModel?->deadline?->toDateString())
                            // ->maxDate(fn($livewire) => method_exists($livewire, 'getMaxStartDate') ? $livewire->getMaxStartDate() : null)
                            ->required()
                            ->disabled(fn (Forms\Get $get) => empty($get('start_date')))
                            ->native(false)
                            ->reactive(),
                        Forms\Components\TextInput::make('status_label')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionTaskCommentsRelationManager::class,
            ActionTaskFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActionTasks::route('/'),
            'create' => Pages\CreateActionTask::route('/create'),
            'edit' => Pages\EditActionTask::route('/{record}/edit'),
            'view' => Pages\ViewActionTask::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
