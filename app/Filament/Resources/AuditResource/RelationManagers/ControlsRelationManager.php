<?php

namespace App\Filament\Resources\AuditResource\RelationManagers;

use App\Filament\Resources\AuditResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ControlsRelationManager extends RelationManager
{
    protected static string $relationship = 'controls';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Controls');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('controlType.title')
                    ->label(__('Control type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label(__('New control'))
                    ->button()
                    ->color('primary')
                    /* ->authorize(
                        fn (User $user) => $user->canCreateFinding($this->getOwnerRecord())
                    ) */
                    ->url(fn () => AuditResource::getUrl('audit_control.create', [
                        'audit' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('follow-up')
                    ->label(__('Follow-up'))
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    /* ->authorize(
                        fn (User $user) => $user->canCreateFinding($this->getOwnerRecord())
                    ) */ // 📌 aqui se cambiará el metodo para que tambien ingrese el responsable (lider del proceso en este caso)
                    ->url(fn ($record) => AuditResource::getUrl('audit_control.view', [
                        'audit' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
