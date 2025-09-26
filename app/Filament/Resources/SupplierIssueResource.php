<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierIssueResource\Pages;
use App\Filament\Resources\SupplierIssueResource\RelationManagers\SupplierIssueFilesRelationManager;
use App\Models\SupplierIssue;
use App\Models\SupplierProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierIssueResource extends Resource
{
    protected static ?string $model = SupplierIssue::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Supplier Issue');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Supplier Issues');
    }

    public static function getNavigationLabel(): string
    {
        return __('Supplier Issues');
    }

    public static function getNavigationGroup(): string
    {
        return __('Supplier');
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Risk identification'))
                    ->description('Record any issues in problems with suppliers')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('cause_id')
                            ->relationship('cause', 'title')
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('issue_date')
                            ->maxDate(now()->format('Y-m-d'))
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'title')
                            ->afterStateUpdated(function (Set $set) {
                                $set('product_id', null);
                                $set('product_title', null);
                            })
                            ->native(false)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('product_id')
                            ->label(__('Product code'))
                            ->relationship(
                                name: 'product',
                                titleAttribute: 'product_code',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('supplier_id', $get('supplier_id'))
                            )
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $product = SupplierProduct::find($state);
                                $set('product_title', $product?->title);
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('product_title')
                            ->label(__('Product Title'))
                            ->formatStateUsing(fn ($record) => $record?->product?->title)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('supplier_lot')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('report_date')
                            ->maxDate(now()->format('Y-m-d'))
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('monetary_impact')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\Textarea::make('supplier_response')
                            ->label(__('Supplier response'))
                            ->rows(3)
                            ->visible(fn ($record) => filled($record?->supplier_response))
                            ->readOnly(),
                        Forms\Components\Textarea::make('supplier_actions')
                            ->label('Supplier actions')
                            ->rows(3)
                            ->visible(fn ($record) => filled($record?->supplier_actions))
                            ->readOnly(),
                        Forms\Components\DatePicker::make('response_date')
                            ->label(__('Response date'))
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->response_date))
                            ->readOnly(),
                        Forms\Components\TextInput::make('effectiveness')
                            ->label(__('Effectiveness'))
                            ->visible(fn ($record) => filled($record?->effectiveness))
                            ->readOnly(),
                        Forms\Components\Textarea::make('evaluation_comment')
                            ->label(__('Evaluation comment'))
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->evaluation_comment))
                            ->readOnly(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('cause.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.title'),
                Tables\Columns\TextColumn::make('product.title')
                    ->limit(30)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->product->title),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier_lot')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('monetary_impact')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'supplier')->orderBy('id', 'asc'),
                    )
                    ->label(__('Status'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            SupplierIssueFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierIssues::route('/'),
            'create' => Pages\CreateSupplierIssue::route('/create'),
            'view' => Pages\ViewSupplierIssue::route('/{record}'),
            // 'edit' => Pages\EditSupplierIssue::route('/{record}/edit'),
        ];
    }
}
