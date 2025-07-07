<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FindingResource\Pages;
use App\Filament\Resources\FindingResource\RelationManagers\ActionsRelationManager;
use App\Models\Audit;
use App\Models\Finding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class FindingResource extends Resource
{
    protected static ?string $model = Finding::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Audit Data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Select::make('audited_sub_process_id')
                            ->label(__('Audited sub process'))
                            ->relationship(
                                'subProcess',
                                'title',
                                modifyQueryUsing: fn ($query, $livewire) => $query->where('process_id', $livewire->ControlModel->audit->involved_process_id)
                            )
                            ->native(false)
                            ->required(),
                        /* Forms\Components\Select::make('audited_sub_process_id')
                            ->label(__('Audited sub process'))
                            ->options(fn (Forms\Get $get): array => Audit::findOrFail($get('audit_id'))
                                ?->involvedSubProcesses
                                ?->pluck('title', 'id')
                                ?->toArray() ?? [])
                            ->native(false)
                            ->required(), */
                        Forms\Components\Select::make('type_of_finding')
                            ->label(__('Type of finding'))
                            ->options([
                                'major_nonconformity' => 'No conformidad mayor',
                                'minor_nonconformity' => 'No conformidad menor',
                                'observation' => 'Observación',
                                'opportunity_for_improvement' => 'Oportunidad de mejora',
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('criteria_not_met')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('responsible_auditor_id')
                            ->relationship(
                                'responsibleAuditor',
                                'name',
                                modifyQueryUsing: fn ($query) => $query->role('auditor')
                            )
                            ->native(false)
                            ->required(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFindings::route('/'),
            'create' => Pages\CreateFinding::route('/create'),
            'view' => Pages\ViewFinding::route('/{record}'),
            'edit' => Pages\EditFinding::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
