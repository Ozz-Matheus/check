<?php

// namespace App\Filament\Resources\AuditResource\RelationManagers;

namespace App\Filament\Resources\ControlResource\RelationManagers;

use App\Filament\Resources\AuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Audited sub process'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type_of_finding')
                    ->label('Tipo de hallazgo')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'major_nonconformity' => 'No conformidad mayor',
                            'minor_nonconformity' => 'No conformidad menor',
                            'observation' => 'Observación',
                            'opportunity_for_improvement' => 'Oportunidad de mejora',
                            default => $state,
                        };
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('New finding')
                    ->button()
                    ->color('primary')
                    ->authorize(
                        fn () => auth()->user()->hasRole('auditor')
                    )
                    ->url(fn () => AuditResource::getUrl('audit_finding.create', [
                        'audit' => $this->getOwnerRecord()->audit_id,
                        'control' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('follow-up')
                    ->label('Follow-up')
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->authorize(
                        fn () => auth()->user()->hasRole('auditor')
                    )// 📌 aqui se cambiará el metodo para que tambien ingrese el responsable (lider del proceso en este caso)
                    ->url(fn ($record) => AuditResource::getUrl('audit_finding.view', [
                        'audit' => $this->getOwnerRecord()->audit_id,
                        'control' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
