<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\Relation;
use OwenIt\Auditing\Models\Audit;

class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(trans('filament-auditing::filament-auditing.column.user_name')),

                Tables\Columns\TextColumn::make('event')
                    ->label(trans('filament-auditing::filament-auditing.column.event'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'created' => __('Created'),
                        'updated' => __('Updated'),
                        'deleted' => __('Deleted'),
                        'restored' => __('Restored'),
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date()
                    ->sortable(),

                Tables\Columns\ViewColumn::make('old_values')
                    ->label(trans('filament-auditing::filament-auditing.column.old_values'))
                    ->view('filament.components.audit-values'),

                Tables\Columns\ViewColumn::make('new_values')
                    ->label(trans('filament-auditing::filament-auditing.column.new_values'))
                    ->view('filament.components.audit-values'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('restore')
                    ->label('Restaurar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->event === 'updated')
                    ->action(fn ($record) => static::restoreAuditSelected($record)),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudits::route('/'),
        ];
    }

    protected static function restoreAuditSelected($audit)
    {
        // Obtener el modelo afectado
        $morphClass = Relation::getMorphedModel($audit->auditable_type)
            ?? $audit->auditable_type;

        $record = $morphClass::find($audit->auditable_id);

        // Validar existencia y tipo de evento
        if (! $record || $audit->event !== 'updated') {

            return Notification::make()
                ->title('No se puede restaurar')
                ->body('El registro no existe o el evento no es "updated".')
                ->warning()
                ->send();
        }

        // Obtener valores antiguos
        $restore = $audit->old_values;

        if (! is_array($restore)) {
            $restore = json_decode($restore, true);
        }

        if (! is_array($restore)) {

            return Notification::make()
                ->title('Sin valores antiguos')
                ->body('No hay datos válidos para restaurar.')
                ->warning()
                ->send();
        }

        // Limpiar y restaurar
        unset($restore['id']); // evita sobrescribir el ID
        $record->fill($restore);
        $record->save();

        // Notificación de éxito
        Notification::make()
            ->title('Registro restaurado')
            ->body('El registro fue restaurado a su estado anterior.')
            ->success()
            ->send();
    }
}
