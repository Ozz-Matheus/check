<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ExistingTenantResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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
                ->label(trans('filament-tenancy::messages.columns.name'))
                ->required()
                ->unique(table: 'tenants', ignoreRecord: true)->live(onBlur: true)
                ->afterStateUpdated(function (Forms\Set $set, $state) {
                    $set('domain', \Str::of($state)->slug()->toString());
                }),

            Forms\Components\Select::make('id')
                ->label('Existing Database (Unique ID)')
                ->options($databases->mapWithKeys(fn ($db) => [$db => $db]))
                ->searchable()
                ->preload()
                ->required()
                ->unique(table: 'tenants', ignoreRecord: true),

            Forms\Components\TextInput::make('domain')
                ->columnSpanFull()
                ->label(trans('filament-tenancy::messages.columns.domain'))
                ->required()
                ->visible(fn ($context) => $context === 'create')
                ->unique(table: 'domains', ignoreRecord: true)
                ->prefix(request()->getScheme().'://')
                ->suffix('.'.request()->getHost()),

            Forms\Components\TextInput::make('email')
                ->label(trans('filament-tenancy::messages.columns.email'))
                ->required()
                ->email(),

            Forms\Components\TextInput::make('phone')
                ->label(trans('filament-tenancy::messages.columns.phone'))
                ->tel(),

            Forms\Components\TextInput::make('password')
                ->label(trans('filament-tenancy::messages.columns.password'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->rule(Password::default())
                ->autocomplete('new-password')
                ->dehydrated(fn ($state): bool => filled($state))
                ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                ->live(debounce: 500)
                ->same('passwordConfirmation'),

            Forms\Components\TextInput::make('passwordConfirmation')
                ->label(trans('filament-tenancy::messages.columns.passwordConfirmation'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->dehydrated(false),

            Forms\Components\Toggle::make('is_active')
                ->label(trans('filament-tenancy::messages.columns.is_active'))
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
