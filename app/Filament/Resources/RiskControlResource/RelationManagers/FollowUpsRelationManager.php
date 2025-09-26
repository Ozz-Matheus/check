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

class FollowUpsRelationManager extends RelationManager
{
    use HasStandardFileUpload;

    protected static string $relationship = 'followUps';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('content'),
                Tables\Columns\TextColumn::make('controlQualification.title'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(function ($record) {
                return RiskResource::getUrl('follow-up.view', [
                    'control' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
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
                            ->placeholder('Follow up comment'),
                        Forms\Components\Select::make('control_qualification_id')
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
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->color('gray')
                    ->icon('heroicon-s-eye')
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
