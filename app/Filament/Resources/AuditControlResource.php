<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditControlResource\Pages;
use App\Filament\Resources\AuditControlResource\RelationManagers\AuditControlFilesRelationManager;
use App\Filament\Resources\AuditControlResource\RelationManagers\AuditControlFindingsRelationManager;
use App\Models\AuditControl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class AuditControlResource extends Resource
{
    protected static ?string $model = AuditControl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Audit control Data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('potentialCauses')
                            ->label(__('Potential causes'))
                            ->relationship(
                                'potentialCauses',
                                'title',
                                modifyQueryUsing: function ($query, $livewire) {
                                    if (isset($livewire->audit_item_id)) {
                                        return $query->where('audit_item_id', $livewire->audit_item_id);
                                    }
                                }
                            )
                            ->preload()
                            ->multiple()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('nature_of_control_id')
                            ->label(__('Nature of control'))
                            ->relationship('natureOfControl', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('control_type_id')
                            ->label(__('Control type'))
                            ->relationship('controlType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('control_periodicity_id')
                            ->label(__('Control periodicity'))
                            ->relationship('controlPeriodicity', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('effect_type_id')
                            ->label(__('Effect type'))
                            ->relationship('effectType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('tests_to_validate_control')
                            ->label(__('Tests to validate control'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('impact_id')
                            ->label(__('Impact'))
                            ->relationship('impact', 'title')
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->impact_id)),
                        Forms\Components\Select::make('probability_id')
                            ->label(__('Probability'))
                            ->relationship('probability', 'title')
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->probability_id)),
                        Forms\Components\Select::make('level_id')
                            ->label(__('Level'))
                            ->relationship('level', 'title')
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->level_id)),
                        Forms\Components\Select::make('classification_id')
                            ->label(__('Classification'))
                            ->relationship('classification', 'title')
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->classification_id)),
                        Forms\Components\Textarea::make('content')
                            ->label(__('Observations'))
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->content)),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            AuditControlFilesRelationManager::class,
            AuditControlFindingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditControls::route('/'),
            'create' => Pages\CreateAuditControl::route('/create'),
            'edit' => Pages\EditAuditControl::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
