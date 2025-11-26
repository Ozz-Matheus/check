<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionTaskResource\Pages;
use App\Filament\Resources\ActionTaskResource\RelationManagers\ActionTaskFollowUpsRelationManager;
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
        return __('Action Task');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Action Tasks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Action task data'))
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
                            ->relationship(
                                'responsibleBy',
                                'name',
                                modifyQueryUsing: fn ($query, $livewire) => $query
                                    ->where('headquarter_id', $livewire->actionModel->headquarter_id)
                                    ->whereHas(
                                        'subProcesses',
                                        fn ($q) => $q->where('sub_process_id', $livewire->actionModel->sub_process_id)
                                    )
                            )
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('Start date'))
                            ->minDate(now()->format('Y-m-d'))
                            ->maxDate(fn ($livewire) => $livewire->actionModel?->limit_date?->toDateString())
                            ->closeOnDateSelection()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('limit_date', null);
                            })
                            ->reactive()
                            ->native(false)
                            ->required(),
                        Forms\Components\DatePicker::make('limit_date')
                            ->label(__('Limit date'))
                            ->minDate(fn (Forms\Get $get) => $get('start_date'))
                            ->maxDate(fn ($livewire) => $livewire->actionModel?->limit_date?->toDateString())
                            ->closeOnDateSelection()
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
                        Forms\Components\Textarea::make('extemporaneous_reason')
                            ->label(__('Reason for extemporaneous closing'))
                            ->visible(fn ($record) => filled($record?->extemporaneous_reason))
                            ->readOnly()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('reason_for_cancellation')
                            ->label(__('Reason for cancellation'))
                            ->visible(fn ($record) => filled($record?->reason_for_cancellation))
                            ->readOnly()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionTaskFollowUpsRelationManager::class,
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
