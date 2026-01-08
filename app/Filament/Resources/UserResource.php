<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\AppNotifier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getNavigationGroup(): string
    {
        return __('User Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 43;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Datos del usuario y roles'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->maxLength(255)
                            ->nullable()
                            ->dehydrated(fn ($state) => filled($state)) // solo lo manda si tiene valor
                            ->required(fn (string $context) => $context === 'create')
                            ->helperText(
                                fn (string $context) => $context === 'edit'
                                    ? __("Leave it blank if you don't want to change your password")
                                    : null
                            ),
                        Forms\Components\CheckboxList::make('roles')
                            ->label(__('Roles'))
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query
                                    ->whereHas('permissions')
                                    ->when(! auth()->user()->hasRole('super_admin'), fn ($q) => $q->where('name', '!=', 'super_admin'))
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => __(Str::headline($record->name)))
                            ->columnSpanFull()
                            ->columns(3),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('Asignación de Subprocesos y lideratos'))
                    ->schema([
                        Forms\Components\CheckboxList::make('subProcesses')
                            ->relationship('subProcesses', 'title')
                            ->label(__('Assigned Sub Processes'))
                            ->disableOptionWhen(function ($value, $record) {
                                return $record?->isLeaderOfSubProcess($value);
                            })
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                $subProcesses = $get('subProcesses');
                                $leaderOf = $get('leaderOf');
                                $filteredLeaderOf = array_intersect($leaderOf, $subProcesses);
                                $set('leaderOf', $filteredLeaderOf);
                            })
                            ->searchable()
                            ->bulkToggleable()
                            ->helperText(
                                fn (string $context) => $context === 'edit'
                                    ? __('The user cannot be unlinked from the subprocess if he is linked to it as a leader.')
                                    : null
                            )
                            ->columns(2),
                        Forms\Components\CheckboxList::make('leaderOf')
                            ->relationship(
                                name: 'leaderOf',
                                titleAttribute: 'title',
                                modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                                    return $query->whereIn('id', $get('subProcesses'));
                                }
                            )
                            ->label(__('Lider de'))
                            ->searchable()
                            ->bulkToggleable()
                            ->helperText(
                                fn (string $context) => $context === 'edit'
                                    ? __('El usuario tendra como opciones de liderato a los subprocesos en los que pertenece.')
                                    : null
                            )
                            ->columns(2),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('Sedes y estado del usuario'))
                    ->schema([
                        Forms\Components\Toggle::make('view_all_headquarters')
                            ->label(__('View all headquarters'))
                            ->helperText(__('It allows the user to view the content of all headquarters.'))
                            ->inline(false),
                        Forms\Components\Toggle::make('interact_with_all_headquarters')
                            ->label(__('Interact with all headquarters'))
                            ->helperText(__('It allows the user to interact with the content of all headquarters.'))
                            ->inline(false),
                        Forms\Components\Select::make('headquarter_id')
                            ->label(__('Headquarters'))
                            ->relationship('headquarter', 'name')
                            ->native(false)
                            ->required(),
                        Forms\Components\Toggle::make('active')
                            ->label(__('Active'))
                            ->helperText(__('Enables or disables user access.'))
                            ->required()
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->copyable()
                    ->copyMessage(__('Email copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('Roles'))
                    ->formatStateUsing(fn ($state) => __(Str::headline($state)))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'indigo',
                        'admin' => 'success',
                        'panel_user' => 'primary',
                        'supplier' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('leaderOf.title')
                    ->label(__('Lider de'))
                    ->limit(30)
                    ->tooltip(
                        fn ($record) => $record->leaderOf->pluck('title')->join(', ')
                    ),
                Tables\Columns\TextColumn::make('headquarter.name')
                    ->label(__('Headquarters'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('Email verified at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ActionGroup::make([

                    DeleteAction::make(), // Envía a papelera
                    RestoreAction::make(),      // Recupera de papelera
                    ForceDeleteAction::make()
                        ->visible(fn ($record): bool => auth()->user()->hasRole('super_admin'))
                        ->before(function ($record, $action) {
                            // Verificamos si el usuario es líder de algún subproceso
                            if ($record->leadSubProcesses()->exists()) {

                                AppNotifier::error(
                                    __('Action Denied'),
                                    __('This user is currently a leader of one or more sub-processes. Please reassign them before deleting.'),
                                    true
                                );
                                $action->halt();
                            }
                        }), // Borrado físico permanente

                ])->color('primary')->link()->label(false)->tooltip('Actions'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->hasRole('super_admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            });
        }

        return $query;
    }
}
