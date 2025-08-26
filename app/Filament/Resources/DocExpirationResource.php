<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocExpirationResource\Pages;
use App\Models\DocExpiration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocExpirationResource extends Resource
{
    protected static ?string $model = DocExpiration::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Doc Expiration');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Doc Expirations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Doc Expirations');
    }

    public static function getNavigationGroup(): string
    {
        return __('Document Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('doc_type_id')
                    ->relationship('docType', 'title')
                    ->label(__('Doc types'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('management_review_years')
                    ->label(__('Management review year'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('central_expiration_years')
                    ->label(__('Central expiration year'))
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('docType.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('management_review_years')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('central_expiration_years')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListDocExpirations::route('/'),
            'create' => Pages\CreateDocExpiration::route('/create'),
            'edit' => Pages\EditDocExpiration::route('/{record}/edit'),
        ];
    }
}
