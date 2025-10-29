<?php

namespace App\Filament\Resources;

use App\Exports\AuditExports\AuditReports\AuditReport;
use App\Filament\Resources\InternalAuditResource\Pages;
use App\Filament\Resources\InternalAuditResource\RelationManagers\AuditItemsRelationManager;
use App\Models\InternalAudit;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class InternalAuditResource extends Resource
{
    protected static ?string $model = InternalAudit::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('Internal Audit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Internal Audits');
    }

    public static function getNavigationLabel(): string
    {
        return __('Internal Audits');
    }

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Internal audit data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\Select::make('process_id')
                            ->label(__('Process'))
                            ->relationship('process', 'title')
                            ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->label(__('Sub process'))
                            ->relationship(
                                name: 'subProcess',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('process_id', $get('process_id'))
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('objective')
                            ->label(__('Objective'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('scope')
                            ->label(__('Scope'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('audit_date')
                            ->label(__('Audit date'))
                            ->minDate(now()->format('Y-m-d'))
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('priority_id')
                            ->label(__('Priority'))
                            ->relationship(
                                name: 'priority',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc')
                            )
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('status_label')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $context) => $context === 'view'),
                        Forms\Components\Select::make('internal_audit_qualification_id')
                            ->label(__('Internal audit qualification'))
                            ->relationship(
                                name: 'internalAuditQualification',
                                titleAttribute: 'title'
                            )
                            ->disabled()
                            ->visible(fn ($record) => filled($record?->internal_audit_qualification_id)),
                        Forms\Components\TextInput::make('qualification_value')
                            ->label(__('Qualification value'))
                            ->suffix('%')
                            ->numeric()
                            ->disabled()
                            ->visible(fn ($record) => filled($record?->qualification_value)),
                        Forms\Components\Select::make('evaluated_by_id')
                            ->label(__('Evaluated by'))
                            ->relationship(
                                name: 'evaluatedBy',
                                titleAttribute: 'name'
                            )
                            ->disabled()
                            ->visible(fn ($record) => filled($record?->evaluated_by_id)),
                        Forms\Components\Textarea::make('observations')
                            ->label(__('Observations'))
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->observations)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification_code')
                    ->label(__('Classification code'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage(__('Classification code copied')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->copyable()
                    ->copyMessage(__('Title copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process')),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process')),
                Tables\Columns\TextColumn::make('audit_date')
                    ->label(__('Audit date'))
                    ->date(),
                Tables\Columns\TextColumn::make('priority.title')
                    ->label(__('Priority')),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName())
                    ->icon(fn ($record) => $record->status->iconName())
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('internalAuditQualification.title')
                    ->label(__('Qualification'))
                    ->badge()
                    ->color(fn ($record) => $record->internalAuditQualification?->color ?? 'gray')
                    ->placeholder(__('Unrated')),
                Tables\Columns\TextColumn::make('qualification_value')
                    ->label(__('Qualification Value'))
                    ->placeholder('-')
                    ->numeric(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('evaluatedBy.name')
                    ->label(__('Evaluated by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('process_id')
                    ->label(__('Process'))
                    ->relationship('process', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('sub_process_id')
                    ->label(__('Sub process'))
                    ->relationship('subProcess', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('priority_id')
                    ->label(__('Priority'))
                    ->relationship(
                        name: 'priority',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'), )
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'internal_audit')->orderBy('id', 'asc'),
                    )
                    ->label(__('Status'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('internal_audit_qualification_id')
                    ->label(__('Qualification'))
                    ->relationship(
                        name: 'internalAuditQualification',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'),
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('export')
                    ->label(__('Export'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {

                        $reportData = AuditReport::make($record->id);

                        return response()->streamDownload(function () use ($reportData) {
                            echo Pdf::loadView('reports.audit-executive', $reportData)->output();
                        }, __('executive-report-internal-audit.pdf'));
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            AuditItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternalAudits::route('/'),
            'create' => Pages\CreateInternalAudit::route('/create'),
            'view' => Pages\ViewInternalAudit::route('/{record}'),
            // 'edit' => Pages\EditInternalAudit::route('/{record}/edit'),
            // Audit items
            'audit-item.create' => \app\Filament\Resources\AuditItemResource\Pages\CreateAuditItem::route('/{internalAudit}/audit-item/create'),
            'audit-item.view' => \app\Filament\Resources\AuditItemResource\Pages\ViewAuditItem::route('/{internalAudit}/audit-item/{record}'),
            // 'audit-item.edit' => \app\Filament\Resources\AuditItemResource\Pages\EditAuditItem::route('/{internalAudit}/audit-item/{record}/edit'),
            // controls
            'control.create' => \app\Filament\Resources\AuditControlResource\Pages\CreateAuditControl::route('/{auditItem}/control/create'),
            'control.view' => \app\Filament\Resources\AuditControlResource\Pages\ViewAuditControl::route('/{auditItem}/control/{record}'),
            // findings
            'finding.view' => \app\Filament\Resources\AuditFindingResource\Pages\ViewAuditFinding::route('/{auditControl}/finding/{record}'),
            // acciones
            'action.create' => \app\filament\resources\ActionResource\Pages\CreateAction::route('/{model_id}/{model}/action/create'),
        ];
    }
}
