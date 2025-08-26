<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskResource\Pages;
use App\Filament\Resources\RiskResource\RelationManagers\ControlsRelationManager;
use App\Models\Risk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk identification'))
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->columns(2)
                    ->schema([
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
                        Forms\Components\Select::make('strategic_context_type_id')
                            ->relationship('strategicContextType', 'title')
                            ->afterStateUpdated(fn (Set $set) => $set('strategic_context_id', null))
                            ->reactive()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('strategic_context_id')
                            ->relationship(
                                name: 'strategicContext',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('strategic_context_type_id', $get('strategic_context_type_id'))
                            )
                            ->preload()
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('risk_description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('risk_category_id')
                            ->relationship('riskCategory', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Repeater::make('potentialCauses')
                            ->relationship()
                            ->label(__('Potential causes'))
                            ->simple(
                                Forms\Components\TextInput::make('title')
                                    ->label(__('Title'))
                                    ->required(),
                            )
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('consequences')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Fieldset::make(__('Inherent risk level'))
                            ->schema([
                                Forms\Components\Select::make('inherent_impact_id')
                                    ->relationship('inherentImpact', 'title')
                                    ->native(false)
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('inherent_probability_id')
                                    ->relationship('inherentProbability', 'title')
                                    ->native(false)
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('inherent_risk_level_id')
                                    ->relationship('inherentLevel', 'title')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),
                                // Parcial para ver el calculo
                                /* Forms\Components\TextInput::make('inherent_risk_level_calculated')
                                    ->dehydrated(false)
                                    ->readOnly()
                                    ->visible(fn (string $context) => $context === 'create'), */
                            ]),
                        Forms\Components\Fieldset::make(__('Residual risk level'))
                            ->schema([
                                // Parcial para ver el calculo del promedio de las calificaciones de controles
                                /* Forms\Components\TextInput::make('control_general_qualification_calculated')
                                    ->label('Promedio de Calificación')
                                    // ->required()
                                    ->columnSpanFull()
                                    ->dehydrated(true), */
                                // me debe de arrojar el item mas cercano al promedio de los score de los controles
                                Forms\Components\Select::make('risk_control_general_qualification_id')
                                    ->relationship('controlGeneralQualificationCalculated', 'title')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->reactive()
                                    ->required(),
                                // Calculo automatico del nivel de riesgo residual
                                Forms\Components\Select::make('residual_risk_level_id')
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
                Tables\Columns\TextColumn::make('process.title'),
                Tables\Columns\TextColumn::make('subProcess.title'),
                /* Tables\Columns\TextColumn::make('risk_description')
                    ->limit(30)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->risk_description), */
                Tables\Columns\TextColumn::make('strategicContextType.title'),
                Tables\Columns\TextColumn::make('strategicContext.title'),
                Tables\Columns\TextColumn::make('riskCategory.title'),
                Tables\Columns\ColumnGroup::make('Risk Inherent', [
                    Tables\Columns\TextColumn::make('inherentImpact.title')
                        ->label(__('Impact')),
                    Tables\Columns\TextColumn::make('inherentProbability.title')
                        ->label(__('Probability')),
                    Tables\Columns\TextColumn::make('inherentLevel.title')
                        ->label(__('Level')),
                ]),
                Tables\Columns\ColumnGroup::make('Risk Residual', [
                    Tables\Columns\TextColumn::make('controlGeneralQualificationCalculated.title')
                        ->label(__('General Qualification')),
                    Tables\Columns\TextColumn::make('residualLevelCalculated.title')
                        ->label(__('Level')),
                ]),
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
                Tables\Filters\SelectFilter::make('process_id')
                    ->relationship('process', 'title')
                    ->label(__('Process'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('sub_process_id')
                    ->relationship('subProcess', 'title')
                    ->label(__('Sub Process'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('strategic_context_type_id')
                    ->relationship('strategicContextType', 'title')
                    ->label(__('Strategic Context Type'))
                    ->native(false),
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
            ControlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRisks::route('/'),
            'create' => Pages\CreateRisk::route('/create'),
            'view' => Pages\ViewRisk::route('/{record}'),
            // 'edit' => Pages\EditRisk::route('/{record}/edit'),
            // Controles
            'control.create' => \app\filament\resources\RiskControlResource\Pages\CreateRiskControl::route('/{risk}/control/create'),
            'control.view' => \app\filament\resources\RiskControlResource\Pages\ViewRiskControl::route('/{risk}/control/{record}'),
            // Segimiento
            'follow-up.view' => \app\filament\resources\RiskControlFollowUpResource\Pages\ViewRiskControlFollowUp::route('/control/{control}/follow-up/{record}'),
        ];
    }
}
