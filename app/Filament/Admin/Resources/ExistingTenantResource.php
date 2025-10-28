<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ExistingTenantResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class ExistingTenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Tenancy';

    protected static ?string $navigationLabel = 'Existing DB Tenants';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-tenancy::messages.group');
    }

    public static function form(Form $form): Form
    {
        $centralDb = config('database.connections.mysql.database');

        $systemSchemas = [
            'information_schema',
            'performance_schema',
            'mysql',
            'sys',
        ];

        $databases = collect(\DB::select('SHOW DATABASES'))
            ->pluck('Database')
            ->reject(fn ($db) => in_array($db, $systemSchemas))
            ->reject(fn ($db) => $db === $centralDb)
            ->values();

        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),

            Forms\Components\Select::make('id')
                ->label('Existing Database (Unique ID)')
                ->options($databases->mapWithKeys(fn ($db) => [$db => $db]))
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('domain')
                ->label('Domain')
                ->required()
                ->columnSpanFull()
                ->prefix(request()->getScheme().'://')
                ->suffix('.'.request()->getHost()),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Forms\Components\TextInput::make('phone')
                ->label('Phone')
                ->tel(),

            Forms\Components\TextInput::make('password')
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->confirmed()
                ->required(),

            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->label('Confirm Password')
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->label('Is Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('id')->label('Database'),
            Tables\Columns\TextColumn::make('domains.domain')->label('Domain'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreateExistingTenant::route('/'),
        ];
    }
}
