<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskTreatmentResource\Pages;
use App\Filament\Resources\RiskTreatmentResource\RelationManagers\ImprovesRelationManager;
use App\Models\RiskTreatment;
use App\Services\RiskService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiskTreatmentResource extends Resource
{
    protected static ?string $model = RiskTreatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk Treatment'))
                    ->description('Start the plan for the process and subprocess to which the study will be done')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Repeater::make('controls')
                            ->relationship()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('potentialCauses')
                                    ->relationship(
                                        'potentialCauses',
                                        'title',
                                        modifyQueryUsing: fn ($query, $livewire) => $query->where('risk_id', $livewire->risk_id)
                                    )
                                    ->preload()
                                    ->multiple()
                                    ->required()
                                    ->visible(fn (string $context) => $context !== 'view')
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('potentialCauses')
                                    ->relationship('potentialCauses', 'title')
                                    ->preload()
                                    ->multiple()
                                    ->visible(fn (string $context) => $context === 'view')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('control_periodicity_id')
                                    ->relationship('periodicity', 'title')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('control_type_id')
                                    ->relationship('controlType', 'title')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('control_qualification_id')
                                    ->relationship('controlQualification', 'title')
                                    ->native(false)
                                    ->reactive()
                                    ->required(),
                            ])
                            ->afterStateUpdated(function (array $state, Set $set) {
                                // Obtener los IDs seleccionados del Repeater
                                $qualificationIds = collect($state)
                                    ->pluck('control_qualification_id')
                                    ->filter(); // Elimina valores null o vacíos

                                if ($qualificationIds->isEmpty()) {
                                    $set('control_general_qualification_calculated', 0);

                                    return;
                                }

                                // Promedio de la calificación para el control general redondeado
                                $average = app(RiskService::class)->averageControlQualification($qualificationIds);
                                $set('control_general_qualification_calculated', $average);

                                // Valor del control general mas cercano al promedio de la calificación
                                $closest = app(RiskService::class)->valueClosestAverage($average);
                                if ($closest) {
                                    $set('risk_control_general_qualification_id', $closest->id);
                                }

                                // Valor del nivel del riesgo dependiendo del promedio calculado
                                $riskLevelCalculated = fn ($livewire) => app(RiskService::class)->riskLevel(
                                    $livewire->riskModel->inherent_impact_id,
                                    $livewire->riskModel->inherent_probability_id,
                                    $average
                                );
                                $set('residual_risk_calculated_level_id', $riskLevelCalculated);
                            })
                            ->columnSpanFull()
                            ->reorderable(false),
                        // Parcial para ver el calculo del promedio de las calificaciones de controles
                        Forms\Components\TextInput::make('control_general_qualification_calculated')
                            ->label('Promedio de Calificación')
                            // ->required()
                            ->columnSpanFull()
                            ->dehydrated(true),
                        // me debe de arrojar el item mas cercano al promedio de los score de los controles
                        Forms\Components\Select::make('risk_control_general_qualification_id')
                            ->relationship('controlGeneralQualification', 'title')
                            ->disabled()
                            ->dehydrated(true)
                            ->reactive()
                            ->required(),
                        // Calculo automatico del nivel de riesgo residual
                        Forms\Components\Select::make('residual_risk_calculated_level_id')
                            ->relationship('residualRiskCalculatedLevel', 'title')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),
                        Forms\Components\Select::make('responsible_executor_id')
                            ->relationship('responsibleExecutor', 'name')
                            ->native(false)
                            ->required(),
                    ]),
                Forms\Components\Section::make(__('Residual risk level'))
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('residual_impact_id')
                            ->relationship('residualImpact', 'title')
                            ->native(false)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('residual_probability_id')
                            ->relationship('residualProbability', 'title')
                            ->native(false)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('residual_risk_level_id')
                            ->relationship('residualLevel', 'title')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('risk_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsible_executor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('risk_control_general_qualification_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('residual_impact_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('residual_probability_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('residual_risk_level_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ImprovesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiskTreatments::route('/'),
            'create' => Pages\CreateRiskTreatment::route('/create'),
            'edit' => Pages\EditRiskTreatment::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
