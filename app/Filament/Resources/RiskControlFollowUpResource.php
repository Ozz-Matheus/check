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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Control Follow-up'))
                    ->description('Determine and record controls to manage risk correctly and in a timely manner')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('control_qualification_id')
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
