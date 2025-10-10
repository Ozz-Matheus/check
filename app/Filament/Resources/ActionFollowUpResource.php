<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionFollowUpResource\Pages;
use App\Filament\Resources\ActionFollowUpResource\RelationManagers\ActionFollowUpFilesRelationManager;
use App\Models\ActionFollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ActionFollowUpResource extends Resource
{
    protected static ?string $model = ActionFollowUp::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Action Follow Up');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Action Follow Ups');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Follow up data'))
                    ->columns(1)
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label(__('Comment'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionFollowUpFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActionFollowUps::route('/'),
            'create' => Pages\CreateActionFollowUp::route('/create'),
            'edit' => Pages\EditActionFollowUp::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
