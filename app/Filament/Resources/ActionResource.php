<?php

namespace App\Filament\Resources;

use App\Exports\ActionExports\ActionExport;
use App\Exports\ActionExports\RelationshipsOfTheActions;
use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers\ActionFollowUpsRelationManager;
use App\Filament\Resources\ActionResource\RelationManagers\ActionTasksRelationManager;
use App\Models\Action;
use App\Models\ActionType;
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

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        $isCorrective = fn (Get $get) => self::isCorrective($get('action_type_id'));

        return $form
            ->schema([
                Forms\Components\Section::make('Action data')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('action_type_id')
                            ->label(__('Action type'))
                            ->relationship('type', 'label')
                            ->native(false)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (! self::isCorrective($state)) {
                                    $set('detection_date', null);
                                    $set('action_analysis_cause_id', null);
                                    $set('root_cause', null);
                                    $set('containment_actions', null);
                                    $set('corrective_action', null);
                                    $set('action_verification_method_id', null);
                                    $set('verification_responsible_by_id', null);
                                }
                            }),
                        Forms\Components\Select::make('source_id')
                            ->label(__('Source'))
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
                            ->label(__('Process'))
                            ->relationship('process', 'title')
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $set('sub_process_id', null);
                                $set('responsible_by_id', null);
                                if (self::isCorrective($get('action_type_id'))) {
                                    $set('verification_responsible_by_id', null);
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn ($livewire) => isset($livewire->originType) ? false : true)
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->label(__('Sub process'))
                            ->relationship(
                                name: 'subProcess',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                            )
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $set('responsible_by_id', null);
                                if (self::isCorrective($get('action_type_id'))) {
                                    $set('verification_responsible_by_id', null);
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn ($livewire) => isset($livewire->originType) ? false : true)
                            ->required(),
                        // Correctiva
                        Forms\Components\DatePicker::make('detection_date')
                            ->label(__('Detection date'))
                            ->maxDate(today())
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required($isCorrective)
                            ->visible($isCorrective)
                            ->dehydrated($isCorrective)
                            ->reactive(),
                        Forms\Components\Select::make('action_analysis_cause_id')
                            ->label(__('Analysis cause'))
                            ->relationship('analysisCause', 'title')
                            ->native(false)
                            ->required($isCorrective)
                            ->visible($isCorrective)
                            ->dehydrated($isCorrective)
                            ->reactive(),
                        Forms\Components\Textarea::make('root_cause')
                            ->label(__('Root cause'))
                            ->columnSpanFull()
                            ->required($isCorrective)
                            ->visible($isCorrective)
                            ->dehydrated($isCorrective)
                            ->reactive(),
                        Forms\Components\Textarea::make('containment_actions')
                            ->label(__('Containment actions'))
                            ->columnSpanFull()
                            ->required($isCorrective)
                            ->visible($isCorrective)
                            ->dehydrated($isCorrective)
                            ->reactive(),
                        Forms\Components\Select::make('action_verification_method_id')
                            ->label(__('Verification method'))
                            ->relationship('verificationMethod', 'title')
                            ->native(false)
                            ->required($isCorrective)
                            ->visible($isCorrective)
                            ->dehydrated($isCorrective)
                            ->reactive(),
                        Forms\Components\Select::make('verification_responsible_by_id')
                            ->label(__('Verification responsible'))
                            ->relationship(
                                name: 'verificationResponsible',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query, Get $get, $livewire) {
                                    $subProcessId = $get('sub_process_id') ?? ($livewire->subProcessId ?? null);

                                    if (! $subProcessId) {
                                        // If no sub-process is selected, return no users.
                                        return $query->whereNull('id');
                                    }

                                    return $query->whereDoesntHave(
                                        'subProcesses',
                                        fn ($q) => $q->where('sub_process_id', $subProcessId)
                                    );
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required($isCorrective)
                            ->visible($isCorrective)
                            ->dehydrated($isCorrective)
                            ->reactive(),
                        // Fin Correctiva
                        Forms\Components\Select::make('responsible_by_id')
                            ->label(__('Responsible'))
                            ->relationship(
                                name: 'responsibleBy',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query, Get $get, $livewire) {
                                    $subProcessId = $get('sub_process_id') ?? ($livewire->subProcessId ?? null);

                                    if (! $subProcessId) {
                                        // If no sub-process is selected, return no users.
                                        return $query->whereNull('id');
                                    }

                                    return $query->whereHas(
                                        'subProcesses',
                                        fn ($q) => $q->where('sub_process_id', $subProcessId)
                                    );
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Textarea::make('expected_impact')
                            ->label(__('Expected impact'))
                            ->columnSpanFull()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('limit_date')
                            ->label(__('Limit date'))
                            ->minDate(today())
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(),
                        Forms\Components\Select::make('priority_id')
                            ->label(__('Priority'))
                            ->relationship(
                                name: 'priority',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                            )
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('status_label')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                        Forms\Components\Textarea::make('reason_for_cancellation')
                            ->label(__('Reason for cancellation'))
                            ->visible(fn ($record) => filled($record?->reason_for_cancellation))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('origin_classification_code')
                    ->label(__('Origin classification code'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->origin_classification_code)
                    ->copyable()
                    ->copyMessage(__('Origin classification code copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('origin_label')
                    ->label(__('Origin'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type.label')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('source.title')
                    ->label(__('Source'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process')),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process')),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->label(__('Registered by')),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->label(__('Responsible')),
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
                Tables\Columns\TextColumn::make('limit_date')
                    ->label(__('Limit date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ending.real_closing_date')
                    ->label(__('Real closing date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cancellation_date')
                    ->label(__('Cancellation date'))
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
            ->filters([
                Tables\Filters\SelectFilter::make('origin_label')
                    ->label(__('Origin'))
                    ->options(fn () => Action::query()->distinct()->whereNotNull('origin_label')->pluck('origin_label', 'origin_label')->all())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->native(false), // Opción para trabajar los filtros
                Tables\Filters\SelectFilter::make('type_id')
                    ->label(__('Type'))
                    ->relationship('type', 'label')
                    ->native(false),
                Tables\Filters\SelectFilter::make('process_id')
                    ->label(__('Process'))
                    ->relationship('process', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('sub_process_id')
                    ->label(__('Sub Process'))
                    ->relationship('subProcess', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('registered_by_id')
                    ->label(__('Registered'))
                    ->relationship('registeredBy', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('responsible_by_id')
                    ->label(__('Responsible'))
                    ->relationship('responsibleBy', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'action_and_task')->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('finished')
                    ->label(__('Finished'))
                    ->options([
                        1 => __('Yes'),
                        0 => __('No'),
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('priority_id')
                    ->label(__('Priority'))
                    ->relationship(
                        name: 'priority',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('source_id')
                    ->label(__('Source'))
                    ->relationship('source', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    BulkAction::make('export')
                        ->label(__('Export base'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new ActionExport($records->pluck('id')->toArray()),
                            'actions_'.now()->format('Y_m_d_His').'.xlsx'
                        ))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('exportar_excel')
                        ->label(__('Export with relationships'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {

                            $actionIds = $records->pluck('id')->all();

                            return Excel::download(
                                new RelationshipsOfTheActions($actionIds),
                                'actions_y_relaciones_'.now()->format('Y_m_d_His').'.xlsx'
                            );

                        })
                        ->deselectRecordsAfterCompletion(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionFollowUpsRelationManager::class,
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
            // Segimiento de accion
            'action-follow-up.view' => \App\Filament\Resources\ActionFollowUpResource\Pages\ViewActionFollowUp::route('/{action}/follow-up/{record}'),
            // Tareas
            'task.create' => \App\Filament\Resources\ActionTaskResource\Pages\CreateActionTask::route('/{action}/tasks/create'),
            'task.view' => \App\Filament\Resources\ActionTaskResource\Pages\ViewActionTask::route('/{action}/tasks/{record}'),
            // Segimiento de tareas
            'task-follow-up.view' => \App\Filament\Resources\ActionTaskFollowUpResource\Pages\ViewActionTaskFollowUp::route('/tasks/{task}/follow-up/{record}'),
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
