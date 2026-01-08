<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TenantResource\Pages;
use App\Filament\Admin\Resources\TenantResource\Pages\CreateTenant;
use App\Filament\Admin\Resources\TenantResource\Pages\ImportDbTenant;
use App\Filament\Admin\Resources\TenantResource\RelationManagers;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    public static function getModelLabel(): string
    {
        return __('Tenant');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tenants');
    }

    public static function getNavigationLabel(): string
    {
        return __('Tenants');
    }

    public static function getNavigationGroup(): string
    {
        return __('Global Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?int $navigationSort = 3;

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

            Forms\Components\Section::make([

                Forms\Components\TextInput::make('name')
                    ->label(trans('filament-tenancy::messages.columns.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->unique(table: 'tenants', ignoreRecord: true)->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Set $set, $state, $livewire) {
                        $set('domain', \Str::of($state)->slug()->toString());
                        if ($livewire instanceof CreateTenant) {
                            $set('id', \Str::of($state)->slug('_')->toString());
                        }
                    }),

                Forms\Components\TextInput::make('id')
                    ->label(trans('filament-tenancy::messages.columns.unique_id'))
                    ->required()
                    ->disabled(fn ($context) => $context !== 'create')
                    ->visible(fn ($livewire) => $livewire instanceof CreateTenant)
                    ->unique(table: 'tenants', ignoreRecord: true),

                Forms\Components\Select::make('id')
                    ->label('Existing Database (Unique ID)')
                    ->options($databases->mapWithKeys(fn ($db) => [$db => $db]))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn ($context) => $context !== 'create')
                    ->visible(fn ($livewire) => $livewire instanceof ImportDbTenant)
                    ->unique(table: 'tenants', ignoreRecord: true),

                Forms\Components\TextInput::make('id')
                    ->label(trans('filament-tenancy::messages.columns.unique_id'))
                    ->disabled()
                    ->visible(fn ($livewire) => ! ($livewire instanceof CreateTenant) &&
                        ! ($livewire instanceof ImportDbTenant)
                    ),

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

            ])->columns(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(trans('filament-tenancy::messages.columns.id'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage(__('ID Tenant copied to clipboard')),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-tenancy::messages.columns.name'))
                    ->description(function ($record) {
                        return request()->getScheme().'://'.$record->domains()->first()?->domain.'.'.config('filament-tenancy.central_domain').'/dashboard';
                    }),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->sortable()
                    ->label(trans('filament-tenancy::messages.columns.is_active'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('Owner'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(trans('filament-tenancy::messages.columns.is_active')),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(trans('filament-tenancy::messages.actions.view'))
                    ->tooltip(trans('filament-tenancy::messages.actions.view'))
                    ->iconButton()
                    ->icon('heroicon-s-link')
                    ->url(fn ($record) => request()->getScheme().'://'.$record->domains()->first()?->domain.'.'.config('filament-tenancy.central_domain').'/'.filament('filament-tenancy')->panel)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('login')
                    ->label(trans('filament-tenancy::messages.actions.login'))
                    ->tooltip(trans('filament-tenancy::messages.actions.login'))
                    ->visible(filament('filament-tenancy')->allowImpersonate)
                    ->requiresConfirmation()
                    ->color('warning')
                    ->iconButton()
                    ->icon('heroicon-s-arrow-left-on-rectangle')
                    ->action(function ($record) {
                        $token = tenancy()->impersonate($record, 1, '/app', 'web');

                        return redirect()->to(request()->getScheme().'://'.$record->domains[0]->domain.'.'.config('filament-tenancy.central_domain').'/login/url?token='.$token->token.'&email='.urlencode($record->email));
                    }),
                Tables\Actions\EditAction::make()
                    ->label(trans('filament-tenancy::messages.actions.edit'))
                    ->tooltip(trans('filament-tenancy::messages.actions.edit'))
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->label(trans('filament-tenancy::messages.actions.delete'))
                    ->tooltip(trans('filament-tenancy::messages.actions.delete'))
                    ->iconButton(),

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
            RelationManagers\DomainsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'import' => Pages\ImportDbTenant::route('/import'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
