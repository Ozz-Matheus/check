<?php

namespace App\Filament\Resources\ActionEndingResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActionEndingFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    protected static ?string $title = 'Action ending files';

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME)))
                ->searchable(),
            Tables\Columns\TextColumn::make('readable_mime_type')
                ->label(__('Type')),
            Tables\Columns\TextColumn::make('readable_size')
                ->label('Size'),
            Tables\Columns\TextColumn::make('created_at')
                ->date(),
        ])
            ->actions([
                Tables\Actions\Action::make('file')
                    ->label('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->url(
                        fn ($record) => $record->url(),
                    )
                    ->openUrlInNewTab(false)
                    ->extraAttributes(fn ($record) => [
                        'download' => $record->name,
                    ]),
            ]);
    }
}
