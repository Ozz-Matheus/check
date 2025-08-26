<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionEndingResource\Pages;
use App\Filament\Resources\ActionEndingResource\RelationManagers\ActionEndingFilesRelationManager;
use App\Models\ActionEnding;
use App\Traits\HasStandardFileUpload;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ActionEndingResource extends Resource
{
    use HasStandardFileUpload;

    protected static ?string $model = ActionEnding::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Ending data'))
                    ->description(__('Enter the completion data and upload your supports'))
                    ->schema([
                        Forms\Components\Textarea::make('real_impact')
                            ->label(__('Real impact'))
                            ->required(),
                        Forms\Components\Textarea::make('result')
                            ->label(__('Result'))
                            ->required(),
                        static::baseFileUpload('path')
                            ->label(__('Support ending files'))
                            ->directory('actions/endings/files')
                            ->multiple()
                            // ->maxParallelUploads(1)
                            ->visible(fn (string $context) => $context === 'create'),
                        Forms\Components\TextInput::make('effectiveness')
                            ->visible(fn ($record) => filled($record?->effectiveness))
                            ->readOnly(),
                        Forms\Components\Textarea::make('evaluation_comment')
                            ->visible(fn ($record) => filled($record?->evaluation_comment))
                            ->readOnly(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActionEndingFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActionEndings::route('/'),
            'create' => Pages\CreateActionEnding::route('/create'),
            'edit' => Pages\EditActionEnding::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
