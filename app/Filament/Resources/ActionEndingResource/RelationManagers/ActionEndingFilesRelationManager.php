<?php

namespace App\Filament\Resources\ActionEndingResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionEndingFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Action ending files');
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('Name'))
                ->limit(30)
                ->tooltip(fn ($record) => $record->name)
                ->copyable()
                ->copyMessage(__('Name copied'))
                ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME))),
            Tables\Columns\TextColumn::make('readable_mime_type')
                ->label(__('Type')),
            Tables\Columns\TextColumn::make('readable_size')
                ->label(__('Size')),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Created at'))
                ->date(),
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
