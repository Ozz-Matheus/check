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

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Task');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tasks');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Task data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('detail')
                            ->label(__('Detail'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('responsible_by_id')
                            ->label(__('Responsible'))
                            ->options(fn ($livewire) => method_exists($livewire, 'getResponsibleUserOptions')
                                ? $livewire->getResponsibleUserOptions()
                                : [])
                            ->preload()
                            ->searchable()
                            ->required()
                            ->visible(fn (string $context) => $context === 'create'),
                        Forms\Components\TextInput::make('responsible_name')
                            ->label(__('Responsible'))
                            ->formatStateUsing(fn ($record) => $record?->responsibleBy?->name ?? '-')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('Start date'))
                            ->minDate(now()->format('Y-m-d'))
                            ->maxDate(fn ($livewire) => method_exists($livewire, 'getMaxStartDate') ? $livewire->getMaxStartDate() : null)
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('deadline', null);
                            })
                            ->reactive()
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('deadline')
                            ->label(__('Deadline'))
                            ->minDate(fn (Forms\Get $get) => $get('start_date'))
                            ->maxDate(fn ($livewire) => method_exists($livewire, 'getMaxStartDate') ? $livewire->getMaxStartDate() : null)
                            ->required()
                            ->disabled(fn (Forms\Get $get) => empty($get('start_date')))
                            ->reactive()
                            ->native(false),
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
