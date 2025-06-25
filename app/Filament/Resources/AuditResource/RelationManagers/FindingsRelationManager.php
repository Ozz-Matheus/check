<?php

namespace App\Filament\Resources\AuditResource\RelationManagers;

use App\Filament\Resources\AuditResource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
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
                        fn (User $user) => $user->canCreateFinding($this->getOwnerRecord())
                    )
                    ->url(fn () => AuditResource::getUrl('audit_finding.create', [
                        'audit' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('follow-up')
                    ->label('Follow-up')
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => AuditResource::getUrl('audit_finding.view', [
                        'audit' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
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
