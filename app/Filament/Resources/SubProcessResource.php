<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubProcessResource\Pages;
use App\Models\SubProcess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubProcessResource extends Resource
{
    protected static ?string $model = SubProcess::class;

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): string
    {
        return __('Global Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->required(fn (string $context) => $context === 'create'),
                Forms\Components\TextInput::make('acronym')
                    ->label(__('Acronym'))
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->required(fn (string $context) => $context === 'create'),
                Forms\Components\Select::make('process_id')
                    ->label(__('Process'))
                    ->relationship('process', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('leader_by_id')
                    ->label(__('Leader'))
                    ->options(fn ($record) => $record->users()->pluck('users.name', 'users.id') ?? [])
                    ->searchable()
                    ->preload()
                    ->required(fn (string $context) => $context === 'edit')
                    ->visible(fn (string $context) => $context === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('acronym')
                    ->label(__('Acronym'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leader.name')
                    ->label(__('Leader'))
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
                    //
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubProcesses::route('/'),
            'create' => Pages\CreateSubProcess::route('/create'),
            'edit' => Pages\EditSubProcess::route('/{record}/edit'),
        ];
    }
}
