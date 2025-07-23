<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Filament\Resources\AuditResource\RelationManagers\ControlsRelationManager;
use App\Models\Audit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Audit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Audits');
    }

    public static function getNavigationLabel(): string
    {
        return __('Audits');
    }

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
                            ->label(__('Audit code'))
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('Start date'))
                            ->minDate(now()->format('Y-m-d'))
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('end_date', null);
                            })
                            ->reactive()
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('End date'))
                            ->minDate(fn (Forms\Get $get) => $get('start_date'))
                            ->required()
                            ->native(false)
                            ->disabled(fn (Forms\Get $get) => $get('start_date') === null),
                        Forms\Components\Textarea::make('objective')
                            ->label(__('Objective'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('scope')
                            ->label(__('Scope'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('involved_process_id')
                            ->label(__('Involved process'))
                            ->relationship('involvedProcess', 'title')
                            ->required()
                            ->preload()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('process_risks', null);
                            })
                            ->reactive()
                            ->searchable(),
                        Forms\Components\Select::make('process_risks')
                            ->label(__('Process risks'))
                            ->relationship(
                                'processRisks',
                                'title',
                                modifyQueryUsing: fn (Forms\Get $get, $query) => $query->where('process_id', $get('involved_process_id'))
                            )
                            ->required()
                            ->preload()
                            ->multiple()
                            ->reactive()
                            ->columnSpanFull()
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
                        Forms\Components\Select::make('audit_criteria_id')
                            ->label(__('Audit criteria'))
                            ->relationship('auditCriteria', 'title')
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\TextInput::make('status_id')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('audit_code')
                    ->label(__('Audit code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('Start date'))
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('End date'))
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('involvedProcess.title')
                    ->label(__('Involved process'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('leaderAuditor.name')
                    ->label(__('Leader auditor'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('auditCriteria.title')
                    ->label(__('Audit criteria'))
                    ->searchable(),
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
            ControlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudits::route('/'),
            'create' => Pages\CreateAudit::route('/create'),
            'view' => Pages\ViewAudit::route('/{record}'),
            // 'edit' => Pages\EditAudit::route('/{record}/edit'),

            'audit_control.create' => \App\Filament\Resources\ControlResource\Pages\CreateControl::route('/{audit}/control/create'),
            'audit_control.view' => \App\Filament\Resources\ControlResource\Pages\ViewControl::route('/{audit}/control/{record}'),
            'audit_finding.create' => \App\Filament\Resources\FindingResource\Pages\CreateFinding::route('/{audit}/control/{control}/finding/create'),
            'audit_finding.view' => \App\Filament\Resources\FindingResource\Pages\ViewFinding::route('/{audit}/control/{control}/finding/{record}'),
            'improve_action.create' => \App\Filament\Resources\ImproveResource\Pages\CreateImprove::route('/{audit}/control/{control}/finding/{finding}/improves/create'),
            'corrective_action.create' => \App\Filament\Resources\CorrectiveResource\Pages\CreateCorrective::route('/{audit}/control/{control}/finding/{finding}/correctives/create'),
            'preventive_action.create' => \App\Filament\Resources\PreventiveResource\Pages\CreatePreventive::route('/{audit}/control/{control}/finding/{finding}/preventives/create'),
        ];
    }
}
