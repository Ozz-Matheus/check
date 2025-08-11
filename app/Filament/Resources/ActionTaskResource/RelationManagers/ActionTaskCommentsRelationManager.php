<?php

namespace App\Filament\Resources\ActionTaskResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\TaskService;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActionTaskCommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->label('Comment')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

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
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\Action::make('create')
                    ->label(__('New task comment'))
                    ->button()
                    ->color('primary')
                    ->form([
                        Textarea::make('content')
                            ->label(__('Comment'))
                            ->required()
                            ->placeholder('Follow up comment'),
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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
