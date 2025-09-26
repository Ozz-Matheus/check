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
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name_affected_person')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('event_type_id')
                            ->relationship('eventType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->label(__('Process'))
                            ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->relationship(
                                name: 'subProcess',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                            )
                            ->label(__('Sub process'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DateTimePicker::make('event_date')
                            ->maxDate(now())
                            // ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('event_site')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification_code')
                    ->label(__('Code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_affected_person')
                    ->searchable(),
                Tables\Columns\TextColumn::make('eventType.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('event_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
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
