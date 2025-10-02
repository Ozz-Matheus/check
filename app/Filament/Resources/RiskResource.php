<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskResource\Pages;
use App\Filament\Resources\RiskResource\RelationManagers\ControlsRelationManager;
use App\Filament\Resources\RiskResource\RelationManagers\RiskActionsRelationManager;
use App\Models\Risk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class RiskResource extends Resource
{
    protected static ?string $model = Risk::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('Risk');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Risks');
    }

    public static function getNavigationLabel(): string
    {
        return __('Risks');
    }

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk identification'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
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
                        Forms\Components\Select::make('strategic_context_type_id')
                            ->label(__('Strategic context type'))
                            ->relationship('strategicContextType', 'label')
                            ->afterStateUpdated(fn (Set $set) => $set('strategic_context_id', null))
                            ->reactive()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('strategic_context_id')
                            ->label(__('Strategic context'))
                            ->relationship(
                                name: 'strategicContext',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('strategic_context_type_id', $get('strategic_context_type_id'))
                            )
                            ->preload()
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('risk_category_id')
                            ->label(__('Risk category'))
                            ->relationship('riskCategory', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Repeater::make('potentialCauses')
                            ->label(__('Potential causes'))
                            ->relationship()
                            ->simple(
                                Forms\Components\TextInput::make('title')
                                    ->label(__('Title'))
                                    ->required(),
                            )
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('consequences')
                            ->label(__('Consequences'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Fieldset::make(__('Inherent risk'))
                            ->schema([
                                Forms\Components\Select::make('inherent_impact_id')
                                    ->label(__('Inherent impact'))
                                    ->relationship(
                                        name: 'inherentImpact',
                                        titleAttribute: 'title',
                                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                                    )
                                    ->native(false)
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('inherent_probability_id')
                                    ->label(__('Inherent probability'))
                                    ->relationship(
                                        name: 'inherentProbability',
                                        titleAttribute: 'title',
                                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                                    )
                                    ->native(false)
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('inherent_risk_level_id')
                                    ->label(__('Inherent risk level'))
                                    ->relationship('inherentLevel', 'title')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),
                            ]),
                        Forms\Components\Fieldset::make(__('Residual risk'))
                            ->schema([
                                // Calculo automatico de la calificacion general del control
                                Forms\Components\Select::make('risk_control_general_qualification_id')
                                    ->label(__('General Qualification'))
                                    ->relationship('controlGeneralQualificationCalculated', 'title')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->reactive()
                                    ->required(),
                                // Calculo automatico del nivel de riesgo residual
                                Forms\Components\Select::make('residual_risk_level_id')
                                    ->label(__('Residual risk level'))
                                    ->relationship('residualLevelCalculated', 'title')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),
                            ])->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification_code')
                    ->label(__('Classification code'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage(__('Classification code copied')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->copyable()
                    ->copyMessage(__('Title copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process')),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process')),
                Tables\Columns\TextColumn::make('strategicContextType.label')
                    ->label(__('Strategic context type')),
                Tables\Columns\TextColumn::make('strategicContext.title')
                    ->label(__('Strategic context')),
                Tables\Columns\TextColumn::make('riskCategory.title')
                    ->label(__('Risk category')),
                Tables\Columns\ColumnGroup::make(__('Inherent risk'), [
                    Tables\Columns\TextColumn::make('inherentImpact.title')
                        ->label(__('Impact')),
                    Tables\Columns\TextColumn::make('inherentProbability.title')
                        ->label(__('Probability')),
                    Tables\Columns\TextColumn::make('inherentLevel.title')
                        ->label(__('Level')),
                ]),
                Tables\Columns\ColumnGroup::make(__('Residual risk'), [
                    Tables\Columns\TextColumn::make('controlGeneralQualificationCalculated.title')
                        ->label(__('General qualification')),
                    Tables\Columns\TextColumn::make('residualLevelCalculated.title')
                        ->label(__('Level')),
                ]),
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
                Tables\Filters\SelectFilter::make('strategic_context_type_id')
                    ->label(__('Strategic context type'))
                    ->relationship('strategicContextType', 'label')
                    ->native(false),
                Tables\Filters\SelectFilter::make('strategic_context_id')
                    ->label(__('Strategic context'))
                    ->relationship('strategicContext', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('risk_category_id')
                    ->label(__('Risk category'))
                    ->relationship('riskCategory', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('inherent_impact_id')
                    ->label(__('Inherent impact'))
                    ->relationship(
                        name: 'inherentImpact',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('inherent_probability_id')
                    ->label(__('Inherent probability'))
                    ->relationship(
                        name: 'inherentProbability',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('inherent_risk_level_id')
                    ->label(__('Inherent risk level'))
                    ->relationship(
                        name: 'inherentLevel',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('risk_control_general_qualification_id')
                    ->label(__('General qualification'))
                    ->relationship(
                        name: 'controlGeneralQualificationCalculated',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('residual_risk_level_id')
                    ->label(__('Residual risk level'))
                    ->relationship(
                        name: 'residualLevelCalculated',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->filtersFormColumns(3)
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
            ControlsRelationManager::class,
            RiskActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRisks::route('/'),
            'create' => Pages\CreateRisk::route('/create'),
            'view' => Pages\ViewRisk::route('/{record}'),
            // 'edit' => Pages\EditRisk::route('/{record}/edit'),
            // Acciones
            'action.create' => \app\filament\resources\ActionResource\Pages\CreateAction::route('/{model_id}/{model}/action/create'),
            // Controles
            'control.create' => \app\filament\resources\RiskControlResource\Pages\CreateRiskControl::route('/{risk}/control/create'),
            'control.view' => \app\filament\resources\RiskControlResource\Pages\ViewRiskControl::route('/{risk}/control/{record}'),
            // Segimiento
            'follow-up.view' => \app\filament\resources\RiskControlFollowUpResource\Pages\ViewRiskControlFollowUp::route('/control/{control}/follow-up/{record}'),
        ];
    }
}
