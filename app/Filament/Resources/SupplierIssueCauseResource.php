<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierIssueCauseResource\Pages;
use App\Models\SupplierIssueCause;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierIssueCauseResource extends Resource
{
    protected static ?string $model = SupplierIssueCause::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Supplier Issue Cause');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Supplier Issue Causes');
    }

    public static function getNavigationLabel(): string
    {
        return __('Supplier Issue Causes');
    }

    public static function getNavigationGroup(): string
    {
        return __('Supplier Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 38;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
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
            'index' => Pages\ListSupplierIssueCauses::route('/'),
            'create' => Pages\CreateSupplierIssueCause::route('/create'),
            'edit' => Pages\EditSupplierIssueCause::route('/{record}/edit'),
        ];
    }
}
