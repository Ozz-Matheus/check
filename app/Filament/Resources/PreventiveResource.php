<?php

namespace App\Filament\Resources;

use App\Enums\RiskEvaluation;
use App\Enums\RiskImpact;
use App\Enums\RiskProbability;
use App\Exports\ActionExport;
use App\Filament\Resources\ActionResource\RelationManagers\ActionTasksRelationManager;
use App\Filament\Resources\PreventiveResource\Pages;
use App\Models\Preventive;
use App\Models\SubProcess;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class PreventiveResource extends Resource
{
    protected static ?string $model = Preventive::class;

    protected static ?string $navigationGroup = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('Preventive action');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Preventive actions');
    }

    public static function getNavigationLabel(): string
    {
        return __('Preventive actions');
    }

    public static function getNavigationGroup(): string
    {
        return __('Actions');
    }

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Action Data'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->columnSpanFull(),
                        Select::make('process_id')
                            ->label(__('Process'))
                            ->relationship('process', 'title')
                            ->afterStateUpdated(function (Set $set) {
                                $set('sub_process_id', null);
                                $set('responsible_by_id', null);
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn ($livewire) => isset($livewire->finding_id) ? false : true)
                            ->required(),
                        Select::make('sub_process_id')
                            ->label(__('Sub process'))
                            ->options(
                                fn (Get $get): Collection => SubProcess::query()
                                    ->where('process_id', $get('process_id'))
                                    ->pluck('title', 'id')
                            )
                            ->afterStateUpdated(fn (Set $set) => $set('responsible_by_id', null))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->visible(fn ($livewire) => isset($livewire->finding_id) ? false : true)
                            ->required(),
                        Select::make('action_origin_id')
                            ->label(__('Origin'))
                            ->relationship('origin', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('responsible_by_id')
                            ->label(__('Responsible'))
                            ->relationship(
                                'responsibleBy',
                                'name',
                                modifyQueryUsing: function ($query, Get $get, $livewire) {
                                    if (isset($livewire->finding_id)) {
                                        return $query->whereHas(
                                            'subProcesses',
                                            fn ($q) => $q->where('sub_process_id', $livewire->findingModel->audited_sub_process_id)
                                        );
                                    }

                                    return $query->whereHas(
                                        'subProcesses',
                                        fn ($q) => $q->where('sub_process_id', $get('sub_process_id'))
                                    );
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        DatePicker::make('detection_date')
                            ->label(__('Detection date'))
                            ->format('Y-m-d')
                            ->required()
                            ->native(false),
                        Select::make('risk_probability')
                            ->label(__('Risk probability'))
                            ->options(RiskProbability::options())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $probability = $get('risk_probability');
                                $impact = $get('risk_impact');
                                $set('risk_evaluation', Preventive::evaluateRiskLevel($probability, $impact));
                            })
                            ->native(false),

                        Select::make('risk_impact')
                            ->label(__('Risk impact'))
                            ->options(RiskImpact::options())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $probability = $get('risk_probability');
                                $impact = $get('risk_impact');
                                $set('risk_evaluation', Preventive::evaluateRiskLevel($probability, $impact));
                            })
                            ->native(false),
                        Select::make('risk_evaluation')
                            ->label(__('Risk evaluation'))
                            ->options(RiskEvaluation::options())
                            ->disabled()
                            ->dehydrated(true),
                        Textarea::make('prevention_action')
                            ->label(__('Prevention action'))
                            ->required(),
                        Textarea::make('effectiveness_indicator')
                            ->label(__('Effectiveness indicator'))
                            ->required(),
                        Textarea::make('expected_impact')
                            ->label(__('Expected impact'))
                            ->required()
                            ->columnSpanFull(),
                        DatePicker::make('deadline')
                            ->label(__('Deadline'))
                            ->minDate(now()->format('Y-m-d'))
                            ->required()
                            ->native(false),
                        TextInput::make('status_label')
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
                Tables\Columns\TextColumn::make('type.label')
                    ->label(__('Type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('origin.title')
                    ->label(__('Origin'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->label(__('Registered by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->label(__('Responsible'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('risk_probability')
                    ->label(__('Risk probability'))
                    ->formatStateUsing(fn ($state) => $state instanceof RiskProbability ? $state->label() : $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('risk_impact')
                    ->label(__('Risk impact'))
                    ->formatStateUsing(fn ($state) => $state instanceof RiskImpact ? $state->label() : $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('risk_evaluation')
                    ->label(__('Risk evaluation'))
                    ->formatStateUsing(fn ($state) => $state instanceof RiskEvaluation ? $state->label() : $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('detection_date')
                    ->label(__('Detection date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('Deadline'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->label(__('Actual closing date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                    BulkAction::make('export')
                        ->label(__('Export selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new ActionExport($records->pluck('id')->toArray()),
                            'actions_preventive_'.now()->format('Y_m_d_His').'.xlsx'
                        )),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ActionTasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreventives::route('/'),
            'create' => Pages\CreatePreventive::route('/create'),
            'view' => Pages\ViewPreventive::route('/{record}'),
            // 'edit' => Pages\EditPreventive::route('/{record}/edit'),
        ];
    }
}
