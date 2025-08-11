<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskPlanResource\Pages;
use App\Filament\Resources\RiskPlanResource\RelationManagers\RisksRelationManager;
use App\Models\RiskPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiskPlanResource extends Resource
{
    protected static ?string $model = RiskPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk plan'))
                    ->description('Start the plan for the process and subprocess to which the study will be done')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('process.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->sortable(),
                Tables\Columns\IconColumn::make('finished')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')
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
            RisksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiskPlans::route('/'),
            'create' => Pages\CreateRiskPlan::route('/create'),
            'view' => Pages\ViewRiskPlan::route('/{record}'),
            // 'edit' => Pages\EditRiskPlan::route('/{record}/edit'),
            'risk.create' => \App\Filament\Resources\RiskResource\Pages\CreateRisk::route('/{riskPlan}/risk/create'),
            'risk.view' => \App\Filament\Resources\RiskResource\Pages\ViewRisk::route('/{riskPlan}/risk/{record}'),
            // Tratamiento de riesgo
            'treatment.create' => \App\Filament\Resources\RiskTreatmentResource\Pages\CreateRiskTreatment::route('/{riskPlan}/risk/{risk}/treatment/create'),
            'treatment.view' => \App\Filament\Resources\RiskTreatmentResource\Pages\ViewRiskTreatment::route('/{riskPlan}/risk/{risk}/treatment/{record}'),
            'treatment.edit' => \App\Filament\Resources\RiskTreatmentResource\Pages\EditRiskTreatment::route('/{riskPlan}/risk/{risk}/treatment/{record}/edit'),
            // Acciones del riesgo
            'improve.create' => \App\Filament\Resources\ImproveResource\Pages\CreateImprove::route('/{riskPlan}/risk/{risk}/treatment/{treatment}/action/create'),
        ];
    }
}
