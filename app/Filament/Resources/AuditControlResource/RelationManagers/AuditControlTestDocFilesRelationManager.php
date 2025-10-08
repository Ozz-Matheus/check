<?php

namespace App\Filament\Resources\AuditControlResource\RelationManagers;

use App\Filament\Resources\InternalAuditResource;
use App\Services\FileService;
use App\Traits\HasStandardFileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditControlTestDocFilesRelationManager extends RelationManager
{
    use HasStandardFileUpload;

    protected static string $relationship = 'testDocumentationFiles';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Test documentation');
    }

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
                    ->copyMessage('Name copied')
                    ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME))),
                Tables\Columns\TextColumn::make('readable_mime_type')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('readable_size')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New test documentation'))
                    ->button()
                    ->color('primary')
                    // 📌 Falta la autorización
                    ->visible($this->getOwnerRecord()->qualified === false)
                    ->form([
                        static::baseFileUpload('path')
                            ->label(__('Support files'))
                            ->directory('internal-audit/audit/control/files')
                            ->multiple()
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        $data['context'] = 'test-documentation';
                        app(FileService::class)->createFiles($owner, $data);

                        redirect(InternalAuditResource::getUrl('control.view', [
                            'auditItem' => $owner->audit_item_id,
                            'record' => $owner->id,
                        ]));
                    }),
            ])
            ->actions([
                //
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
