<?php

namespace App\Filament\Resources\SupplierIssueResource\RelationManagers;

use App\Filament\Resources\SupplierIssueResource;
use App\Services\FileService;
use App\Traits\HasStandardFileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

use function __;

class SupplierIssueFilesRelationManager extends RelationManager
{
    use HasStandardFileUpload;

    protected static string $relationship = 'files';

    protected static ?string $title = 'Support Files';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
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
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New support files'))
                    ->color('primary')
                    ->form([
                        static::baseFileUpload('path')
                            ->label(__('Support follow-up files'))
                            ->directory('supplier-issue/files')
                            ->multiple()
                            ->columnSpanFull(),
                    ])
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->action(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        app(FileService::class)->createFiles($owner, $data);

                        redirect(SupplierIssueResource::getUrl('view', [
                            'record' => $this->getOwnerRecord()->id,
                        ]));
                    }),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
