<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionTaskFollowUpResource\Pages;
use App\Filament\Resources\ActionTaskFollowUpResource\RelationManagers\FollowUpfilesRelationManager;
use App\Models\ActionTaskFollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ActionTaskFollowUpResource extends Resource
{
    protected static ?string $model = ActionTaskFollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Task Follow-up'))
                    ->description('Determine and record controls to manage risk correctly and in a timely manner')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            FollowUpfilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActionTaskFollowUps::route('/'),
            'create' => Pages\CreateActionTaskFollowUp::route('/create'),
            'edit' => Pages\EditActionTaskFollowUp::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
