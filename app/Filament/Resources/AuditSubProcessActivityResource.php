<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditSubProcessActivityResource\Pages;
use App\Models\AuditSubProcessActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditSubProcessActivityResource extends Resource
{
    protected static ?string $model = AuditSubProcessActivity::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Audit Sub Process Activity');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Audit Sub Process Activities');
    }

    public static function getNavigationLabel(): string
    {
        return __('Audit Sub Process Activities');
    }

    public static function getNavigationGroup(): string
    {
        return __('Audit Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('process_id')
                    ->relationship('process', 'title')
                    ->label(__('Process'))
                    ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('sub_process_id')
                    ->relationship(
                        name: 'subProcess',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                    )
                    ->label(__('Sub process'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('process.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
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
            'index' => Pages\ListAuditSubProcessActivities::route('/'),
            'create' => Pages\CreateAuditSubProcessActivity::route('/create'),
            'edit' => Pages\EditAuditSubProcessActivity::route('/{record}/edit'),
        ];
    }
}
