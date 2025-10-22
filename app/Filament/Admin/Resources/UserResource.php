<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-tenancy::messages.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('User Data'))
                    ->columns(3)
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
                                    ? __("Leave it blank if you don't want to change your password.")
                                    : null
                            ),
                        // Forms\Components\Toggle::make('active')
                        //     ->label(__('Active'))
                        //     ->helperText(__('Enables or disables user access.'))
                        //     ->required()
                        //     ->default(true),
                        Forms\Components\Select::make('roles')
                            ->label(__('Roles'))
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->options(function () {
                                $roles = \Spatie\Permission\Models\Role::pluck('name', 'id');

                                if (! auth()->user()->hasRole('super_admin')) {
                                    $roles = $roles->reject(fn ($name) => $name === 'super_admin');
                                }

                                return $roles;
                            }),
                    ]),
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
                    ->copyMessage(__('Email copied to clipboard'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('Roles'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('Email verified at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
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
                    //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
