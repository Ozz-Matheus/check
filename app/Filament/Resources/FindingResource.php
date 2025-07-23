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

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Finding');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Findings');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Finding data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Select::make('audited_sub_process_id')
                            ->label(__('Audited sub process'))
                            ->relationship(
                                'subProcess',
                                'title',
                                modifyQueryUsing: fn ($query, $livewire) => $query->where('process_id', $livewire->controlModel->audit->involved_process_id)
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
                        Forms\Components\Select::make('finding_type')
                            ->label(__('Finding type'))
                            ->options([
                                'major_nonconformity' => 'No conformidad mayor',
                                'minor_nonconformity' => 'No conformidad menor',
                                'observation' => 'Observación',
                                'opportunity_for_improvement' => 'Oportunidad de mejora',
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('criteria_not_met')
                            ->label(__('Criteria not met'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('responsible_auditor_id')
                            ->label(__('Responsible auditor'))
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
