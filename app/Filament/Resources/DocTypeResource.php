<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocTypeResource\Pages;
use App\Models\DocType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocTypeResource extends Resource
{
    protected static ?string $model = DocType::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Doc Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Doc Types');
    }

    public static function getNavigationLabel(): string
    {
        return __('Doc Types');
    }

    public static function getNavigationGroup(): string
    {
        return __('Document Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label(__('Label'))
                    ->unique()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('acronym')
                    ->label(__('Acronym'))
                    ->unique()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Label'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('acronym')
                    ->label(__('Acronym'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->date()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocTypes::route('/'),
            'create' => Pages\CreateDocType::route('/create'),
            'edit' => Pages\EditDocType::route('/{record}/edit'),
        ];
    }
}
