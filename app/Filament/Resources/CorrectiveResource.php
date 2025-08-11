<?php

namespace App\Filament\Resources;

use App\Exports\ActionExport;
use App\Filament\Resources\ActionResource\RelationManagers\ActionTasksRelationManager;
use App\Filament\Resources\CorrectiveResource\Pages;
use App\Models\Corrective;
use App\Models\SubProcess;
use Filament\Forms;
use Filament\Forms\Components\Section;
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

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Action Data')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->afterStateUpdated(function (Set $set) {
                                $set('sub_process_id', null);
                                $set('responsible_by_id', null);
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
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
                            ->required(),
                        Forms\Components\Select::make('responsible_by_id')
                            ->label('Responsible')
                            ->relationship(
                                'responsibleBy',
                                'name',
                                modifyQueryUsing: fn ($query, Get $get) => $query->whereHas(
                                    'subProcesses',
                                    fn ($q) => $q->where('sub_process_id', $get('sub_process_id'))
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),
                        Forms\Components\Textarea::make('expected_impact')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('deadline')
                            ->minDate(now()->format('Y-m-d'))
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('status_label')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('process.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('origin.title')
                    ->label('Origin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsibleBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
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
