<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskStrategicContextResource\Pages;
use App\Models\RiskStrategicContext;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiskStrategicContextResource extends Resource
{
    protected static ?string $model = RiskStrategicContext::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Risk Strategic Context');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Risk Strategic Contexts');
    }

    public static function getNavigationLabel(): string
    {
        return __('Risk Strategic Contexts');
    }

    public static function getNavigationGroup(): string
    {
        return __('Risk Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('strategic_context_type_id')
                    ->label(__('Strategic context type'))
                    ->relationship('strategicContextType', 'label')
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('strategicContextType.label')
                    ->label(__('Strategic context type')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('strategic_context_type_id')
                    ->label(__('Strategic Context Type'))
                    ->relationship('strategicContextType', 'label')
                    ->native(false),
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
            'index' => Pages\ListRiskStrategicContexts::route('/'),
            'create' => Pages\CreateRiskStrategicContext::route('/create'),
            'edit' => Pages\EditRiskStrategicContext::route('/{record}/edit'),
        ];
    }
}
