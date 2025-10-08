<?php

namespace App\Filament\Resources\RiskControlResource\RelationManagers;

use App\Filament\Resources\RiskResource;
use App\Services\FileService;
use App\Services\RiskControlService;
use App\Traits\HasStandardFileUpload;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FollowUpsRelationManager extends RelationManager
{
    use HasStandardFileUpload;

    protected static string $relationship = 'followUps';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Follow ups');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label(__('Comment'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->content)
                    ->copyable()
                    ->copyMessage(__('Comment copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('controlQualification.title')
                    ->label(__('Control qualification')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return RiskResource::getUrl('follow-up.view', [
                    'control' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                Tables\Filters\SelectFilter::make('control_qualification_id')
                    ->label(__('Control qualification'))
                    ->relationship(
                        name: 'controlQualification',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                    )
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New control follow-up'))
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\Textarea::make('content')
                            ->label(__('Comment'))
                            ->required()
                            ->placeholder(__('Follow up comment')),
                        Forms\Components\Select::make('control_qualification_id')
                            ->label(__('Control qualification'))
                            ->relationship(
                                name: 'controlQualification',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                            )
                            ->native(false)
                            ->required(),
                        static::baseFileUpload('path')
                            ->label(__('Support follow-up files'))
                            ->directory('risks/controls/follow-ups/files')
                            ->multiple()
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        $followUp = $owner->followUps()->create([
                            'content' => $data['content'],
                            'control_qualification_id' => $data['control_qualification_id'],
                        ]);
                        app(FileService::class)->createFiles($followUp, $data);
                        app(RiskControlService::class)->updateQualities($owner, $data);

                        redirect(RiskResource::getUrl('control.view', [
                            'risk' => $owner->risk_id,
                            'record' => $owner->id,

                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn ($record) => RiskResource::getUrl('follow-up.view', [
                        'control' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
