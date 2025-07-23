<?php

namespace App\Filament\Resources\FindingResource\RelationManagers;

use App\Filament\Resources\AuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'actions';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Actions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('type.label')
                    ->label(__('Action type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('origin.title')
                    ->label(__('Origin'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->label(__('Registered by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->label(__('Responsible'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('Deadline'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->label(__('Actual closing date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('View'))
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => $record->getFilamentUrl('view'))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('id', 'desc')
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New action'))
                    ->button()
                    ->color('primary')
                    ->url(function () {
                        $finding = $this->getOwnerRecord();

                        if (! $finding) {
                            return null;
                        }

                        $actionType = $finding->getMappedActionType();

                        return AuditResource::getUrl("{$actionType}_action.create", [
                            'audit' => $finding->control->audit_id,
                            'control' => $finding->control_id,
                            'finding' => $finding->id,
                        ]);
                    }),
            ])->recordUrl(false);
    }
}
