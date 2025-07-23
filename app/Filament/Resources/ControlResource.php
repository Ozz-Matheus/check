<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ControlResource\Pages;
use App\Filament\Resources\ControlResource\RelationManagers\ControlFilesRelationManager;
use App\Filament\Resources\ControlResource\RelationManagers\FindingsRelationManager;
use App\Models\Control;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ControlResource extends Resource
{
    protected static ?string $model = Control::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Control');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Controls');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Control Data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('control_type_id')
                            ->label(__('Control type'))
                            ->relationship(
                                'controlType',
                                'title',
                                modifyQueryUsing: fn ($livewire, $query) => $query->whereIn('process_risk_id', $livewire->auditModel->processRisks->pluck('id') ?? [])
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('comment')
                            ->label(__('Comment'))
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
