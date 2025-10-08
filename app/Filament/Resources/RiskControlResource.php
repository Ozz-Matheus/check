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

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Risk Control');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Risk Controls');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Control data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('potentialCauses')
                            ->label(__('Potential causes'))
                            ->relationship(
                                name: 'potentialCauses',
                                titleAttribute: 'title',
                                modifyQueryUsing: function ($query, $livewire) {
                                    if (isset($livewire->risk_id)) {
                                        return $query->where('risk_id', $livewire->risk_id);
                                    }
                                }
                            )
                            ->preload()
                            ->multiple()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('control_periodicity_id')
                            ->label(__('Control periodicity'))
                            ->relationship('periodicity', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('control_type_id')
                            ->label(__('Control type'))
                            ->relationship('controlType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('control_qualification_id')
                            ->label(__('Control qualification'))
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
