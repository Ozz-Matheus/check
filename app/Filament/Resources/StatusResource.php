<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Statuses');
    }

    public static function getNavigationLabel(): string
    {
        return __('Statuses');
    }

    public static function getNavigationGroup(): string
    {
        return __('Global Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Internal title'))
                    ->required()
                    ->disabled(fn () => ! auth()->user()?->hasRole('super_admin'))
                    ->dehydrated(fn () => auth()->user()?->hasRole('super_admin'))
                    ->helperText(__('This is the status identifier. Only super admins can edit it.'))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('label')
                    ->label(__('Display name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(fn ($record) => $record?->title ?? __('State title'))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('color')
                    ->label(__('Color'))
                    ->required(),

                Forms\Components\TextInput::make('icon')
                    ->label(__('Icon'))
                    ->required(),

                Forms\Components\TextInput::make('context')
                    ->label(__('Context'))
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('context')
                    ->label(__('Context'))
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->visible(function () {
                        return auth()->user()->hasRole('super_admin');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Color and Icon'))
                    ->badge()
                    ->color(fn ($record) => $record->color)
                    ->icon(fn ($record) => $record->icon)
                    ->searchable(),
                Tables\Columns\IconColumn::make('protected')
                    ->label(__('Protected'))
                    ->boolean(),
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
                    //
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
        ];
    }
}
