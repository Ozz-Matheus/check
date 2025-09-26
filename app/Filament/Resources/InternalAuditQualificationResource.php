<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternalAuditQualificationResource\Pages;
use App\Models\InternalAuditQualification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InternalAuditQualificationResource extends Resource
{
    protected static ?string $model = InternalAuditQualification::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Internal Audit Qualification');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Internal Audit Qualifications');
    }

    public static function getNavigationLabel(): string
    {
        return __('Internal Audit Qualifications');
    }

    public static function getNavigationGroup(): string
    {
        return __('Audit Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('min')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('max')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max')
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
            'index' => Pages\ListInternalAuditQualifications::route('/'),
            'create' => Pages\CreateInternalAuditQualification::route('/create'),
            'edit' => Pages\EditInternalAuditQualification::route('/{record}/edit'),
        ];
    }
}
