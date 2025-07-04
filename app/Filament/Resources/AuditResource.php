<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Filament\Resources\AuditResource\RelationManagers\FindingsRelationManager;
use App\Models\Audit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): string
    {
        return __('Audits');
    }

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Audit Data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('audit_code')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('start_date')
                            ->minDate(now()->format('Y-m-d'))
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('end_date', null);
                            })
                            ->reactive()
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->minDate(fn (Forms\Get $get) => $get('start_date'))
                            ->required()
                            ->disabled(fn (Forms\Get $get) => $get('start_date') === null),
                        Forms\Components\Textarea::make('objective')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('scope')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('involvedProcess')
                            ->relationship('involvedProcess', 'title')
                            ->required()
                            ->preload()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('risks', null);
                                $set('controls', null);
                            })
                            ->reactive()
                            ->searchable(),
                        Forms\Components\Select::make('risks')
                            ->relationship(
                                'risks',
                                'title',
                                modifyQueryUsing: fn (Forms\Get $get, $query) => $query->where('process_id', $get('involvedProcess'))
                            )
                            ->required()
                            ->preload()
                            ->multiple()
                            ->reactive()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('controls', null))
                            // Pendiente una manera para que cuando se quite un resk, si ya hay controls seleccionados
                            // de ese risk se quiten solo esos automaticamente
                            ->columnSpanFull()
                            ->searchable(),
                        Forms\Components\Select::make('controls')
                            ->relationship(
                                'controls',
                                'title',
                                modifyQueryUsing: fn (Forms\Get $get, $query) => $query->whereIn('risk_id', $get('risks') ?? [])
                            )
                            ->required()
                            ->preload()
                            ->multiple()
                            ->reactive()
                            ->columnSpanFull()
                            ->disabled(fn (Forms\Get $get) => $get('risks') === null)
                            ->searchable(),
                        Forms\Components\Select::make('leader_auditor_id')
                            ->label(__('Leader auditor'))
                            ->relationship(
                                'leaderAuditor',
                                'name',
                                modifyQueryUsing: fn ($query) => $query->role('auditor') // Filtro para que solo muestre auditores
                            )
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('assignedAuditors')
                            ->label(__('Assigned auditors'))
                            ->relationship(
                                'assignedAuditors',
                                'name',
                                modifyQueryUsing: fn ($query) => $query->role('auditor') // Filtro para que solo muestre auditores
                            )
                            ->required()
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('audit_criteria_id')
                            ->relationship('auditCriteria', 'title')
                            ->required()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('audit_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('involvedProcess.title')
                    ->searchable(),
                /* Tables\Columns\TextColumn::make('involvedSubProcesses.title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->involvedSubProcesses->pluck('title')->join(', ')), */
                Tables\Columns\TextColumn::make('leaderAuditor.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedAuditors.name')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->assignedAuditors->pluck('name')->join(', ')),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('auditCriteria.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            FindingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudits::route('/'),
            'create' => Pages\CreateAudit::route('/create'),
            'view' => Pages\ViewAudit::route('/{record}'),
            // 'edit' => Pages\EditAudit::route('/{record}/edit'),

            'audit_finding.create' => \App\Filament\Resources\FindingResource\Pages\CreateFinding::route('/{audit}/finding/create'),
            'audit_finding.view' => \App\Filament\Resources\FindingResource\Pages\ViewFinding::route('/{audit}/finding/{record}'),
            'improve_action.create' => \App\Filament\Resources\ImproveResource\Pages\CreateImprove::route('/{audit}/finding/{finding}/improves/create'),
            'corrective_action.create' => \App\Filament\Resources\CorrectiveResource\Pages\CreateCorrective::route('/{audit}/finding/{finding}/correctives/create'),
            'preventive_action.create' => \App\Filament\Resources\PreventiveResource\Pages\CreatePreventive::route('/{audit}/finding/{finding}/preventives/create'),
        ];
    }
}
