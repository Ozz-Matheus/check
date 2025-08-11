<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskResource\Pages;
use App\Models\Risk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiskResource extends Resource
{
    protected static ?string $model = Risk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk identification'))
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('strategic_context_type_id')
                            ->relationship('strategicContextType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('strategic_context_id')
                            ->relationship('strategicContext', 'title')
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
                        Forms\Components\Section::make(__('Inherent risk level'))
                            ->description('Prevent abuse by limiting the number of requests per period')
                            ->columns(2)
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
                                Forms\Components\TextInput::make('inherent_risk_level_calculated')
                                    ->dehydrated(false)
                                    ->readOnly()
                                    ->visible(fn (string $context) => $context === 'create'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('risk_plan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('strategic_context_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('strategic_context_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('risk_category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inherent_impact_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inherent_probability_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inherent_risk_level_id')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRisks::route('/'),
            'create' => Pages\CreateRisk::route('/create'),
            'edit' => Pages\EditRisk::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
