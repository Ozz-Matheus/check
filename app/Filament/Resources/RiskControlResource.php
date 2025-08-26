<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskControlResource\Pages;
use App\Filament\Resources\RiskControlResource\RelationManagers\FollowUpsRelationManager;
use App\Models\RiskControl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class RiskControlResource extends Resource
{
    protected static ?string $model = RiskControl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk control'))
                    ->description('Determine and record controls to manage risk correctly and in a timely manner')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('potentialCauses')
                            ->relationship(
                                'potentialCauses',
                                'title',
                                modifyQueryUsing: fn ($query, $livewire) => $query->where('risk_id', $livewire->risk_id)
                            )
                            ->preload()
                            ->multiple()
                            ->required()
                            ->visible(fn (string $context) => $context !== 'view')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('potentialCauses')
                            ->relationship(
                                'potentialCauses',
                                'title',
                            )
                            ->multiple()
                            ->visible(fn (string $context) => $context === 'view')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('control_periodicity_id')
                            ->relationship('periodicity', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('control_type_id')
                            ->relationship('controlType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('control_qualification_id')
                            ->relationship('controlQualification', 'title')
                            ->native(false)
                            ->visible(fn (string $context) => $context === 'view')
                            ->required(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            FollowUpsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiskControls::route('/'),
            'create' => Pages\CreateRiskControl::route('/create'),
            'edit' => Pages\EditRiskControl::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
