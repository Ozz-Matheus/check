<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentAndAccidentResource\Pages;
use App\Filament\Resources\IncidentAndAccidentResource\RelationManagers\IncidentAndAccidentActionsRelationManager;
use App\Models\IncidentAndAccident;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncidentAndAccidentResource extends Resource
{
    protected static ?string $model = IncidentAndAccident::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('Incident And Accident');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Incident And Accidents');
    }

    public static function getNavigationLabel(): string
    {
        return __('Incident And Accidents');
    }

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Incident and accident identification'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name_affected_person')
                            ->label(__('Name affected person'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('event_type_id')
                            ->label(__('Event type'))
                            ->relationship('eventType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('process_id')
                            ->label(__('Process'))
                            ->relationship('process', 'title')
                            ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->label(__('Sub process'))
                            ->relationship(
                                name: 'subProcess',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DateTimePicker::make('event_date')
                            ->label(__('Event date'))
                            ->maxDate(now())
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('event_site')
                            ->label(__('Event site'))
                            ->required()
                            ->maxLength(255),
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
                        Forms\Components\TextInput::make('created_by_name')
                            ->label(__('Created By'))
                            ->formatStateUsing(fn ($record) => $record?->createdBy?->name ?? 'Sin usuario')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                        Forms\Components\Textarea::make('observations')
                            ->label(__('Observations'))
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->observations)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification_code')
                    ->label(__('Code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_affected_person')
                    ->label(__('Name affected person'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->name_affected_person)
                    ->searchable(),
                Tables\Columns\TextColumn::make('eventType.title')
                    ->label(__('Event type')),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process')),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process')),
                Tables\Columns\TextColumn::make('priority.title')
                    ->label(__('Priority')),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName())
                    ->icon(fn ($record) => $record->status->iconName())
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('event_date')
                    ->label(__('Event date'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event_type_id')
                    ->label(__('Event type'))
                    ->relationship('eventType', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('process_id')
                    ->label(__('Process'))
                    ->relationship('process', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('sub_process_id')
                    ->label(__('Sub process'))
                    ->relationship('subProcess', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('priority_id')
                    ->label(__('Priority'))
                    ->relationship(
                        name: 'priority',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'incident_and_accident')->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            IncidentAndAccidentActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncidentAndAccidents::route('/'),
            'create' => Pages\CreateIncidentAndAccident::route('/create'),
            'view' => Pages\ViewIncidentAndAccident::route('/{record}'),
            // 'edit' => Pages\EditIncidentAndAccident::route('/{record}/edit'),
            // Acciones
            'action.create' => \app\filament\resources\ActionResource\Pages\CreateAction::route('/{model_id}/{model}/action/create'),
        ];
    }
}
