<?php

namespace App\Filament\Resources\RiskPlanResource\RelationManagers;

use App\Filament\Resources\RiskPlanResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RisksRelationManager extends RelationManager
{
    protected static string $relationship = 'risks';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('risk_description')
            ->columns([
                Tables\Columns\TextColumn::make('strategicContextType.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('strategicContext.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('riskCategory.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('inherentImpact.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('inherentProbability.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('inherentLevel.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->recordUrl(function ($record) {
                return RiskPlanResource::getUrl('risk.view', [
                    'riskPlan' => $this->getOwnerRecord()->id,
                    'record' => $record->id,
                ]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New risk'))
                    ->button()
                    ->color('primary')
                    // 📌 Falta la autorización
                    // 📌 Falta la visibilidad
                    ->url(fn () => RiskPlanResource::getUrl('risk.create', [
                        'riskPlan' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
