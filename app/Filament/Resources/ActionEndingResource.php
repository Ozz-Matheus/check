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
use Filament\Tables;
use Filament\Tables\Table;

class ActionEndingResource extends Resource
{
    use HasStandardFileUpload;

    protected static ?string $model = ActionEnding::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ending Data')
                    ->description('Enter the completion data and upload your supports')
                    ->schema([
                        Forms\Components\Textarea::make('real_impact')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('result')
                            ->required()
                            ->columnSpanFull(),
                        static::baseFileUpload('path')
                            ->label('Support files')
                            ->directory('actions/support/files')
                            ->multiple()
                            ->maxParallelUploads(1)
                            ->columnSpanFull()
                            ->visible(fn (string $context) => $context === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             Tables\Columns\TextColumn::make('action_id')
    //                 ->numeric()
    //                 ->sortable(),
    //             Tables\Columns\TextColumn::make('created_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //             Tables\Columns\TextColumn::make('updated_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

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
