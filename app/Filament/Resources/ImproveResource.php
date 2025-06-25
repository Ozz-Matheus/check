<?php

namespace App\Filament\Resources;

use App\Exports\ActionExport;
use App\Filament\Resources\ActionResource\Forms\ImproveSchema;
use App\Filament\Resources\ActionResource\RelationManagers\ActionTasksRelationManager;
use App\Filament\Resources\ImproveResource\Pages;
use App\Models\Improve;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class ImproveResource extends Resource
{
    protected static ?string $model = Improve::class;

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): string
    {
        return __('Actions');
    }

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema(ImproveSchema::get());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type.label')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('origin.title')
                    ->label('Origin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                    BulkAction::make('export')
                        ->label('Export selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new ActionExport($records->pluck('id')->toArray()),
                            'actions_improve_'.now()->format('Y_m_d_His').'.xlsx'
                        )),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
            ActionTasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImproves::route('/'),
            'create' => Pages\CreateImprove::route('/create'),
            'view' => Pages\ViewImprove::route('/{record}'),
            // 'edit' => Pages\EditImprove::route('/{record}/edit'),
        ];
    }
}
