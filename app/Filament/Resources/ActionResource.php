<?php

namespace App\Filament\Resources;

use App\Exports\ActionExport;
use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers\ActionTasksRelationManager;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\SubProcess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('Action');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Actions');
    }

    public static function getNavigationLabel(): string
    {
        return __('Actions');
    }

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Action Data')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('action_type_id')
                            ->relationship('type', 'label')
                            ->native(false)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (! self::isCorrective($state)) {
                                    $set('detection_date', null);
                                    $set('containment_actions', null);
                                    $set('action_analysis_cause_id', null);
                                    $set('corrective_action', null);
                                    $set('action_verification_method_id', null);
                                    $set('verification_responsible_by_id', null);
                                }
                            }),
                        Forms\Components\Select::make('source_id')
                            ->label('Source')
                            ->relationship('source', 'title')
                            ->native(false)
                            ->required(fn ($record) => is_null($record))
                            ->visible(function ($record, $livewire) {
                                if ($record) {
                                    // En view: mostrar solo si origin_type está vacío
                                    return ! filled($record->origin_type);
                                }

                                // En creación: mostrar solo si originType en Livewire está vacío
                                return ! filled($livewire->originType);
                            })
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->afterStateUpdated(function (Set $set) {
                                $set('sub_process_id', null);
                                $set('responsible_by_id', null);
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn ($livewire) => isset($livewire->originType) ? false : true)
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->label('Sub Process')
                            ->options(
                                fn (Get $get): Collection => SubProcess::query()
                                    ->where('process_id', $get('process_id'))
                                    ->pluck('title', 'id')
                            )
                            ->afterStateUpdated(fn (Set $set) => $set('responsible_by_id', null))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn ($livewire) => isset($livewire->originType) ? false : true)
                            ->required(),
                        // Correctiva
                        Forms\Components\DatePicker::make('detection_date')
                            ->maxDate(now()->format('Y-m-d'))
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Select::make('action_analysis_cause_id')
                            ->relationship('analysisCause', 'title')
                            ->native(false)
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Textarea::make('root_cause')
                            ->columnSpanFull()
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Textarea::make('containment_actions')
                            ->columnSpanFull()
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Textarea::make('corrective_actions')
                            ->columnSpanFull()
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Select::make('action_verification_method_id')
                            ->relationship('verificationMethod', 'title')
                            ->native(false)
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Select::make('verification_responsible_by_id')
                            ->relationship('verificationResponsible', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        // Fin Correctiva
                        Forms\Components\Select::make('responsible_by_id')
                            ->relationship(
                                name: 'responsibleBy',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query, Get $get, $livewire) {
                                    if (isset($livewire->originType)) {
                                        return $query->whereHas(
                                            'subProcesses',
                                            fn ($q) => $q->where('sub_process_id', $livewire->subProcessId)
                                        );
                                    }

                                    return $query->whereHas(
                                        'subProcesses',
                                        fn ($q) => $q->where('sub_process_id', $get('sub_process_id'))
                                    );
                                }
                            )
                            ->label('Responsible')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Textarea::make('expected_impact')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('limit_date')
                            ->minDate(now()->format('Y-m-d'))
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(),
                        Forms\Components\TextInput::make('status_label')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                        Forms\Components\Textarea::make('reason_for_cancellation')
                            ->label('Reason for cancellation')
                            ->visible(fn ($record) => filled($record?->reason_for_cancellation))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('origin_label')
                    ->label(__('Origin'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type.label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\IconColumn::make('finished')
                    ->label(__('Finished'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ending.real_closing_date')
                    ->label(__('Real Closing Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cancellation_date')
                    ->label(__('Cancellation Date'))
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
            ->filters([
                //
                Tables\Filters\SelectFilter::make('type_id')
                    ->relationship('type', 'label')
                    ->multiple()
                    ->preload()
                    ->label(__('Type')),
                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'action_and_task')->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->preload()
                    ->label(__('Status')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                    BulkAction::make('export')
                        ->label('Export selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new ActionExport($records->pluck('id')->toArray()),
                            'actions_improve_'.now()->format('Y_m_d_His').'.xlsx'
                        )),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionTasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'view' => Pages\ViewAction::route('/{record}'),
            // 'edit' => Pages\EditAction::route('/{record}/edit'),
            // Finalización
            'ending.create' => \App\Filament\Resources\ActionEndingResource\Pages\CreateActionEnding::route('/{action}/endings/create'),
            'ending.view' => \App\Filament\Resources\ActionEndingResource\Pages\ViewActionEnding::route('/{action}/endings/{record}'),
            // Tareas
            'task.create' => \App\Filament\Resources\ActionTaskResource\Pages\CreateActionTask::route('/{action}/tasks/create'),
            'task.view' => \App\Filament\Resources\ActionTaskResource\Pages\ViewActionTask::route('/{action}/tasks/{record}'),
            // Segimiento de tareas
            'follow-up.view' => \App\Filament\Resources\ActionTaskFollowUpResource\Pages\ViewActionTaskFollowUp::route('/tasks/{task}/follow-up/{record}'),
        ];
    }

    protected static function getCorrectiveTypeId(): int
    {
        return cache()->rememberForever('corrective_action_type_id', function () {
            return ActionType::where('name', 'corrective')->value('id');
        });
    }

    protected static function isCorrective($actionTypeId): bool
    {
        return (int) $actionTypeId === self::getCorrectiveTypeId();
    }
}
