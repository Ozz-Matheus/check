<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskProbabilityResource\Pages;
use App\Models\RiskProbability;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiskProbabilityResource extends Resource
{
    protected static ?string $model = RiskProbability::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Risk Probability');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Risk Probabilities');
    }

    public static function getNavigationLabel(): string
    {
        return __('Risk Probabilities');
    }

    public static function getNavigationGroup(): string
    {
        return __('Risk Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?int $navigationSort = 19;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('weight')
                    ->label(__('Weight'))
                    ->helperText('1 - 5')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label(__('Weight'))
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListRiskProbabilities::route('/'),
            'create' => Pages\CreateRiskProbability::route('/create'),
            'edit' => Pages\EditRiskProbability::route('/{record}/edit'),
        ];
    }
}
