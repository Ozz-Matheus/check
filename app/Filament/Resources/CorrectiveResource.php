<?php

namespace App\Filament\Resources;

use App\Exports\ActionExport;
use App\Filament\Resources\ActionResource\RelationManagers\ActionTasksRelationManager;
use App\Filament\Resources\CorrectiveResource\Pages;
use App\Models\Corrective;
use App\Models\SubProcess;
use App\Models\User;
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

class CorrectiveResource extends Resource
{
    protected static ?string $model = Corrective::class;

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): string
    {
        return __('Actions');
    }

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Action Data')
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
                            ->required(),
                        Select::make('sub_process_id')
                            ->label(__('Sub Process'))
                            ->options(
                                fn (Get $get): Collection => SubProcess::query()
                                    ->where('process_id', $get('process_id'))
                                    ->pluck('title', 'id')
                            )
                            ->afterStateUpdated(fn (Set $set) => $set('responsible_by_id', null))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Select::make('action_origin_id')
                            ->label(__('Origin'))
                            ->relationship('origin', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('responsible_by_id')
                            ->label(__('Responsible'))
                            ->options(
                                fn (Get $get): array => User::whereHas(
                                    'subProcesses',
                                    fn ($query) => $query->where('sub_process_id', $get('sub_process_id'))
                                )
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        DatePicker::make('detection_date')
                            ->label(__('Detection date'))
                            ->format('Y-m-d')
                            ->required(),
                        Textarea::make('containment_action')
                            ->label(__('Containment action'))
                            ->columnSpanFull()
                            ->required(),
                        Select::make('action_analysis_cause_id')
                            ->label(__('Analysis cause'))
                            ->relationship('analysisCause', 'title')
                            ->required()
                            ->native(false),
                        Textarea::make('corrective_action')
                            ->label(__('Corrective action'))
                            ->columnSpanFull()
                            ->required(),
                        Select::make('action_verification_method_id')
                            ->label(__('Verification method'))
                            ->relationship('verificationMethod', 'title')
                            ->required()
                            ->native(false),
                        Select::make('verification_responsible_by_id')
                            ->label(__('Verification responsible'))
                            ->relationship('verificationResponsible', 'name')
                            ->required()
                            ->native(false),
                        DatePicker::make('deadline')
                            ->label(__('Deadline'))
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
                Tables\Columns\TextColumn::make('detection_date')
                    ->label(__('Detection date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('analysisCause.title')
                    ->label(__('Analysis cause'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('verificationMethod.title')
                    ->label(__('Verification method'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('verificationResponsible.name')
                    ->label(__('Verification responsible'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('Deadline'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('verification_date')
                    ->label(__('Verification date'))
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
                    ->label(__('Updated at'))
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
                        ->label('Export selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new ActionExport($records->pluck('id')->toArray()),
                            'actions_corrective_'.now()->format('Y_m_d_His').'.xlsx'
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
            'index' => Pages\ListCorrectives::route('/'),
            'create' => Pages\CreateCorrective::route('/create'),
            'view' => Pages\ViewCorrective::route('/{record}'),
            // 'edit' => Pages\EditCorrective::route('/{record}/edit'),
        ];
    }
}
