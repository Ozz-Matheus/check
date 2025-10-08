<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiskControlFollowUpResource\Pages;
use App\Filament\Resources\RiskControlFollowUpResource\RelationManagers\FollowUpFilesRelationManager;
use App\Models\RiskControlFollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class RiskControlFollowUpResource extends Resource
{
    protected static ?string $model = RiskControlFollowUp::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Risk Control Follow Up');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Risk Control Follow Ups');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Control follow-up'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label(__('Comment'))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('control_qualification_id')
                            ->label(__('Control qualification'))
                            ->relationship('controlQualification', 'title')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            FollowUpFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiskControlFollowUps::route('/'),
            'create' => Pages\CreateRiskControlFollowUp::route('/create'),
            'edit' => Pages\EditRiskControlFollowUp::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
