<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocEndingResource\Pages;
use App\Models\DocEnding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocEndingResource extends Resource
{
    protected static ?string $model = DocEnding::class;

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): string
    {
        return __('Document Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Internal title'))
                    ->required()
                    ->helperText(__('This is the Final Disposition identifier.'))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('label')
                    ->label(__('Display name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(fn ($record) => $record?->title ?? __('Final Disposition title'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
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
            'index' => Pages\ListDocEndings::route('/'),
            'create' => Pages\CreateDocEnding::route('/create'),
            'edit' => Pages\EditDocEnding::route('/{record}/edit'),
        ];
    }
}
