<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskControlQualificationResource\Pages;
use App\Models\RiskControlQualification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RiskControlQualificationResource extends Resource
{
    protected static ?string $model = RiskControlQualification::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Risk Control Qualification');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Risk Control Qualifications');
    }

    public static function getNavigationLabel(): string
    {
        return __('Risk Control Qualifications');
    }

    public static function getNavigationGroup(): string
    {
        return __('Risk Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('context')
                    ->label(__('Context'))
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('score')
                    ->label(__('Score'))
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('context')
                    ->label(__('Context')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('score')
                    ->label(__('Score'))
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
            'index' => Pages\ListRiskControlQualifications::route('/'),
            'create' => Pages\CreateRiskControlQualification::route('/create'),
            'edit' => Pages\EditRiskControlQualification::route('/{record}/edit'),
        ];
    }
}
