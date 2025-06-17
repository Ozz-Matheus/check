<?php

namespace App\Filament\Resources\ActionEndingResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ActionEndingFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'ActionEndingFiles';

    protected static ?string $title = 'Action Ending Files';

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME)))
                ->searchable(),
            Tables\Columns\TextColumn::make('mime_type')
                ->label('Type')
                ->formatStateUsing(fn (string $state) => strtoupper(Str::after($state, '/'))),
            Tables\Columns\TextColumn::make('readable_size')
                ->label('Size'),
            Tables\Columns\TextColumn::make('created_at')
                ->date('l, d \d\e F \d\e Y'),
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
