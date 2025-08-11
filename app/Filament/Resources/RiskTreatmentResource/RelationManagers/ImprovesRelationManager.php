<?php

namespace App\Filament\Resources\RiskTreatmentResource\RelationManagers;

use App\Filament\Resources\RiskPlanResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovesRelationManager extends RelationManager
{
    protected static string $relationship = 'actionImproves';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New action'))
                    ->button()
                    ->color('primary')
                    ->visible(function () {
                        return str($this->getPageClass())->contains('View');
                    })
                    ->url(function () {
                        $record = $this->getOwnerRecord()->load('risk');

                        // dd($record);
                        return RiskPlanResource::getUrl('improve.create', [
                            'riskPlan' => $record->risk->risk_plan_id,
                            'risk' => $record->risk->id,
                            'treatment' => $record->id,
                        ]);
                    })/* ->openUrlInNewTab() */,
            ]) // ->recordUrl(true)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
