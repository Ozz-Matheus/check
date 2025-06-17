<?php

namespace App\Filament\Resources\ActionTaskResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\TaskService;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActionTaskFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'taskFiles';

    protected static ?string $title = 'Files';

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME)))
                ->searchable(),
            Tables\Columns\TextColumn::make('mime_type')
                ->label('Type')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        'application/pdf' => 'PDF',
                        'application/msword' => 'Word',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel',
                        default => __('Otro'),
                    };
                }),
            Tables\Columns\TextColumn::make('readable_size')
                ->label('Size'),
            Tables\Columns\TextColumn::make('created_at')
                ->date('l, d \d\e F \d\e Y'),
        ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('New task file')
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\FileUpload::make('path')
                            ->label('Files Data')
                            ->storeFileNamesIn('name')
                            ->disk('public')
                            ->directory('actions/tasks/files')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ])
                            ->maxSize(10240) // en KB, 10MB ejemplo
                            ->helperText('Permitido: PDF, DOC, DOCX, XLS, XLSX (máx. 10MB)')
                            ->multiple()
                            ->maxParallelUploads(1)
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->authorize(
                        fn () => app(TaskService::class)->canTaskUploadFollowUp($this->getOwnerRecord())
                    )
                    ->action(function (array $data) {
                        app(TaskService::class)->createFiles($this->getOwnerRecord(), $data);
                        redirect(ActionResource::getUrl('action_tasks.view', [
                            'action_id' => $this->getOwnerRecord()->action_id,
                            'record' => $this->getOwnerRecord()->id,
                        ]));
                    }),
                /* Tables\Actions\CreateAction::make(), */
            ])
            ->actions([
                //
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
