<?php

namespace App\Filament\Resources\ActionEndingResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionEndingFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'ActionEndingFiles';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Action ending support files');
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('Name'))
                ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME)))
                ->searchable(),
            Tables\Columns\TextColumn::make('readable_mime_type')
                ->label(__('Type')),
            Tables\Columns\TextColumn::make('readable_size')
                ->label(__('Size')),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Created at'))
                ->date('l, d \d\e F \d\e Y'),
        ])
            ->actions([
                Tables\Actions\Action::make('file')
                    ->label(__('Download'))
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
