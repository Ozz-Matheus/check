<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditFindingResource\Pages;
use App\Filament\Resources\AuditFindingResource\RelationManagers\AuditFindingActionsRelationManager;
use App\Models\AuditFinding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class AuditFindingResource extends Resource
{
    protected static ?string $model = AuditFinding::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Control Finding');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Control Findings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Finding data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('finding_type_id')
                            ->label(__('Finding type'))
                            ->relationship('findingType', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('criteria')
                            ->label(__('Criteria'))
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            AuditFindingActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditFindings::route('/'),
            'create' => Pages\CreateAuditFinding::route('/create'),
            'edit' => Pages\EditAuditFinding::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
