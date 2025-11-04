<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeadquarterResource\Pages;
use App\Models\Headquarter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeadquarterResource extends Resource
{
    protected static ?string $model = Headquarter::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Headquarter');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Headquarters');
    }

    public static function getNavigationLabel(): string
    {
        return __('Headquarters');
    }

    public static function getNavigationGroup(): string
    {
        return __('Headquarter Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?int $navigationSort = 44;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label(__('Display name'))
                    ->required()
                    ->unique(table: 'headquarters', ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get, $component) {
                        if ($component->getLivewire() instanceof CreateRecord) {
                            $set('name', \Str::of($state)->slug()->toString());
                        }
                    })
                    ->maxLength(255)
                    ->placeholder('Ej. Colombia')
                    ->helperText('Este nombre se usará para mostrar la sede en el sistema.'),

                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Display name')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
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
            'index' => Pages\ListHeadquarters::route('/'),
            'create' => Pages\CreateHeadquarter::route('/create'),
            'edit' => Pages\EditHeadquarter::route('/{record}/edit'),
        ];
    }
}
