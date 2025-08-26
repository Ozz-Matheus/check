<?php

namespace App\Filament\Resources\ActionTaskResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\FileService;
use App\Services\TaskService;
use App\Traits\HasStandardFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActionTaskFollowUpsRelationManager extends RelationManager
{
    use HasStandardFileUpload;

    protected static string $relationship = 'followUps';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->content),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return ActionResource::getUrl('follow-up.view', [
                    'task' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New task follow-up'))
                    ->button()
                    ->color('primary')
                    ->form([
                        Textarea::make('content')
                            ->label(__('Comment'))
                            ->required()
                            ->placeholder('Follow up comment'),
                        static::baseFileUpload('path')
                            ->label(__('Support follow-up files'))
                            ->directory('risks/controls/follow-ups/files')
                            ->multiple()
                            ->columnSpanFull(),
                    ])
                    ->authorize(auth()->id() === $this->getOwnerRecord()->responsible_by_id)
                    ->visible(fn () => app(TaskService::class)->canViewCreateTaskFollowUp($this->getOwnerRecord()))
                    ->action(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        $followUp = $owner->followUps()->create([
                            'content' => $data['content'],
                        ]);
                        app(FileService::class)->createFiles($followUp, $data);
                        app(TaskService::class)->updateTaskStatus($owner);

                        redirect(ActionResource::getUrl('task.view', [
                            'action' => $this->getOwnerRecord()->action_id,
                            'record' => $this->getOwnerRecord()->id,

                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->color('gray')
                    ->icon('heroicon-s-eye')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn ($record) => ActionResource::getUrl('follow-up.view', [
                        'task' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
