<?php

namespace App\Filament\Resources\AuditResource\RelationManagers;

use App\Filament\Resources\AuditResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ControlsRelationManager extends RelationManager
{
    protected static string $relationship = 'controls';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('controlType.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('New finding')
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
                    ->label('Follow-up')
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
