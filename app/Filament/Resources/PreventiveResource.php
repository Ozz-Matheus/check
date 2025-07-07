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
                Section::make('Action Data')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('process_id')
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
                            ->label('Sub Process')
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
                            ->label('Origin')
                            ->relationship('origin', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('responsible_by_id')
                            ->label('Responsible')
                            ->relationship(
                                'responsibleBy',
                                'name',
                                modifyQueryUsing: function ($query, Get $get, $livewire) {
                                    if (isset($livewire->finding_id)) {
                                        return $query->whereHas(
                                            'subProcesses',
                                            fn ($q) => $q->where('sub_process_id', $livewire->FindingModel->audited_sub_process_id)
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
                            ->format('Y-m-d')
                            ->required(),
                        Select::make('risk_probability')
                            ->label(__('Probability'))
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
                            ->label(__('Impact'))
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
                            ->required(),
                        Textarea::make('effectiveness_indicator')
                            ->required(),

                        Textarea::make('expected_impact')
                            ->required()
                            ->columnSpanFull(),
                        DatePicker::make('deadline')
                            ->minDate(now()->format('Y-m-d'))
                            ->required(),
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
                    ->label(__('Responsible by'))
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated_at'))
                    ->dateTime()
                    ->sortable()
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
