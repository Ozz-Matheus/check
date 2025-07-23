<?php

namespace App\Filament\Resources\ActionTaskResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\TaskService;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionTaskCommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'taskComments';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Comments');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label(__('Content'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->content),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date('l, d \d\e F \d\e Y'),

            ])
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\Action::make('create')
                    ->label(__('New comment'))
                    ->button()
                    ->color('primary')
                    ->form([
                        Textarea::make('content')
                            ->label(__('Comment'))
                            ->required()
                            ->placeholder(__('Follow up comment')),
                    ])
                    ->authorize(
                        fn () => app(TaskService::class)->canTaskUploadFollowUp($this->getOwnerRecord())
                    )
                    ->action(function (array $data) {

                        app(TaskService::class)->createComment($this->getOwnerRecord(), $data);

                        redirect(ActionResource::getUrl('action_tasks.view', [
                            'action' => $this->getOwnerRecord()->action_id,
                            'record' => $this->getOwnerRecord()->id,

                        ]));
                    }),

                /* Tables\Actions\CreateAction::make(), */
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
