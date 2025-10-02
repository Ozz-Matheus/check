<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditItemResource\Pages;
use App\Filament\Resources\AuditItemResource\RelationManagers\ControlsRelationManager;
use App\Models\AuditItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class AuditItemResource extends Resource
{
    protected static ?string $model = AuditItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Audit item data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('activity_id')
                            ->label(__('Activity'))
                            ->relationship(
                                name: 'activity',
                                titleAttribute: 'title',
                                modifyQueryUsing: function (Builder $query, $livewire) {
                                    if (isset($livewire->internalAuditModel)) {
                                        $internalAudit = $livewire->internalAuditModel;

                                        // Primero, filtra las actividades por el sub-proceso de la auditoría interna.
                                        $query->where('sub_process_id', $internalAudit->sub_process_id);

                                        // Luego, obtenga los IDs de las actividades ya utilizadas en esta auditoría.
                                        $existingActivityIds = AuditItem::where('internal_audit_id', $internalAudit->id)
                                            ->pluck('activity_id');

                                        // Excluya las actividades ya utilizadas de la selección.
                                        return $query->whereNotIn('id', $existingActivityIds);
                                    }
                                },
                            )
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('risk_description')
                            ->label(__('Risk description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('risk_category_id')
                            ->label(__('Risk category'))
                            ->relationship('riskCategory', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Repeater::make('potentialCauses')
                            ->label(__('Potential causes'))
                            ->relationship()
                            ->simple(
                                Forms\Components\TextInput::make('title')
                                    ->label(__('Title'))
                                    ->required(),
                            )
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('consequences')
                            ->label(__('Consequences'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('general_level_id')
                            ->label(__('General level'))
                            ->relationship('generalLevel', 'title')
                            ->native(false)
                            ->visible(fn ($record, $context) => filled($record?->general_level_id) && $context === 'view'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ControlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditItems::route('/'),
            'create' => Pages\CreateAuditItem::route('/create'),
            'edit' => Pages\EditAuditItem::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
