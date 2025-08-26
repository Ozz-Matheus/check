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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Action Data')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('action_type_id')
                            ->relationship('type', 'label')
                            ->native(false)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (! self::isCorrective($state)) {
                                    $set('detection_date', null);
                                    $set('containment_action', null);
                                    $set('action_analysis_cause_id', null);
                                    $set('corrective_action', null);
                                    $set('action_verification_method_id', null);
                                    $set('verification_responsible_by_id', null);
                                }
                            }),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->afterStateUpdated(function (Set $set) {
                                $set('sub_process_id', null);
                                $set('responsible_by_id', null);
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
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
                            ->required(),
                        // Correctiva
                        Forms\Components\DatePicker::make('detection_date')
                            ->format('Y-m-d')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->visible(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->dehydrated(fn (Get $get) => self::isCorrective($get('action_type_id')))
                            ->reactive(),
                        Forms\Components\Textarea::make('containment_action')
                            ->columnSpanFull()
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
                        Forms\Components\Textarea::make('corrective_action')
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
                                'responsibleBy',
                                'name',
                                modifyQueryUsing: fn ($query, Get $get) => $query->whereHas(
                                    'subProcesses',
                                    fn ($q) => $q->where('sub_process_id', $get('sub_process_id'))
                                )
                            )
                            ->label('Responsible')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Textarea::make('expected_impact')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('deadline')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
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
            ->filters([
                //
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
