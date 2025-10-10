<?php

namespace App\Filament\Resources\ActionResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Services\ActionService;
use App\Services\FileService;
use App\Traits\HasStandardFileUpload;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionFollowUpsRelationManager extends RelationManager
{
    use HasStandardFileUpload;

    protected static string $relationship = 'followUps';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Follow ups');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('content')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label(__('Comment'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->content),
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
                    ->label(__('New follow-up'))
                    ->color('primary')
                    ->form([
                        Textarea::make('content')
                            ->label(__('Comment'))
                            ->required()
                            ->placeholder(__('Follow up comment')),
                        static::baseFileUpload('path')
                            ->label(__('Support follow-up files'))
                            ->directory('actions/follow-ups/files')
                            ->multiple()
                            ->columnSpanFull(),
                    ])
                    ->authorize($this->getOwnerRecord()->responsible_by_id === auth()->id() && auth()->user()->can('create_action::follow::up'))
                    ->visible(fn () => app(ActionService::class)->canViewCreateTaskAndFollowUp($this->getOwnerRecord()->status_id))
                    ->action(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        $followUp = $owner->followUps()->create([
                            'content' => $data['content'],
                        ]);
                        app(FileService::class)->createFiles($followUp, $data);
                        app(ActionService::class)->changeActionStatusToExecution($owner);

                        redirect(ActionResource::getUrl('view', [
                            'record' => $this->getOwnerRecord()->id,

                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn ($record) => ActionResource::getUrl('action-follow-up.view', [
                        'action' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
