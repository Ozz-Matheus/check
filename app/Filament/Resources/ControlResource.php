<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\RelationManagers\FindingsRelationManager;
use App\Filament\Resources\ControlResource\Pages;
use App\Filament\Resources\ControlResource\RelationManagers\ControlFilesRelationManager;
use App\Models\Control;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ControlResource extends Resource
{
    protected static ?string $model = Control::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Control Data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('control_type_id')
                            ->relationship(
                                'controlType',
                                'title',
                                modifyQueryUsing: fn ($livewire, $query) => $query->whereIn('risk_id', $livewire->AuditModel->risks->pluck('id') ?? [])
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('comment')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('status_id')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ControlFilesRelationManager::class,
            FindingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListControls::route('/'),
            'create' => Pages\CreateControl::route('/create'),
            'view' => Pages\ViewControl::route('/{record}'),
            // 'edit' => Pages\EditControl::route('/{record}/edit'),
        ];
    }
}
