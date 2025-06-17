<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionEndingResource\Pages;
use App\Filament\Resources\ActionEndingResource\RelationManagers\ActionEndingFilesRelationManager;
use App\Models\ActionEnding;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActionEndingResource extends Resource
{
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
                        Forms\Components\FileUpload::make('path')
                            ->label('Support files')
                            ->storeFileNamesIn('name')
                            ->disk('public')
                            ->directory('actions/support/files')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
                            ])
                            ->maxSize(10240) // en KB, 10MB ejemplo
                            ->helperText('Allowed types: PDF, DOC, DOCX, XLS, XLSX (max. 10MB)')
                            ->multiple()
                            ->maxParallelUploads(1)
                            ->columnSpanFull()
                            ->visible(fn (string $context) => $context === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
