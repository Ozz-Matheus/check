<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierPortalResource\Pages;
use App\Filament\Resources\SupplierPortalResource\RelationManagers\SupplierPortalFilesRelationManager;
use App\Models\SupplierPortal;
use App\Services\SupplierPortalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierPortalResource extends Resource
{
    protected static ?string $model = SupplierPortal::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('Supplier Portal');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Suppliers Portal');
    }

    public static function getNavigationLabel(): string
    {
        return __('Suppliers Portal');
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Suplier issue data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->maxLength(255),
                        Forms\Components\Select::make('cause_id')
                            ->label(__('Cause'))
                            ->relationship('cause', 'title')
                            ->native(false),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('entry_date')
                            ->label(__('Entry date'))
                            ->native(false),
                        Forms\Components\DatePicker::make('report_date')
                            ->label(__('Report date'))
                            ->native(false),
                        Forms\Components\Select::make('supplier_id')
                            ->label(__('Supplier'))
                            ->relationship('supplier', 'name')
                            ->native(false),
                        Forms\Components\Select::make('product_id')
                            ->label(__('Product code'))
                            ->relationship('product', 'product_code')
                            ->native(false),
                        Forms\Components\Textarea::make('product_title')
                            ->label(__('Product Title'))
                            ->formatStateUsing(fn ($record) => $record?->product?->title)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('amount')
                            ->label(__('Amount'))
                            ->extraAttributes([
                                'onkeydown' => 'if(event.key === "e" || event.key === "E") event.preventDefault();',
                            ])
                            ->numeric(),
                        Forms\Components\TextInput::make('supplier_lot')
                            ->label(__('Supplier lot'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('status_label')
                            ->label(__('Status'))
                            ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('effectiveness')
                            ->label(__('Effectiveness'))
                            ->visible(fn ($record) => filled($record?->effectiveness)),
                        Forms\Components\Textarea::make('evaluation_comment')
                            ->label(__('Evaluation comment'))
                            // ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->evaluation_comment)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title)
                    ->copyable()
                    ->copyMessage(__('Title copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('report_date')
                    ->label(__('Report date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cause.title')
                    ->label(__('Cause')),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label(__('Supplier')),
                Tables\Columns\TextColumn::make('product.title')
                    ->label(__('Product'))
                    ->limit(30)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->product->title),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier_lot')
                    ->label(__('Supplier lot'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->supplier_lot)
                    ->copyable()
                    ->copyMessage(__('Supplier lot copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName())
                    ->icon(fn ($record) => $record->status->iconName())
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('responses.effectiveness')
                    ->label(__('Effectiveness'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'yes' => 'success',
                        'no' => 'danger',
                        'partial' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('-'),
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
            ->recordUrl(null)
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('cause_id')
                    ->label(__('Cause'))
                    ->relationship('cause', 'title')
                    ->native(false),
                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'supplier_issue')->where('title', '!=', 'open')->orderBy('id', 'asc'),
                    )
                    ->native(false),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->actions([
                Tables\Actions\Action::make('view_record')
                    ->label(__('View'))
                    ->color('gray')
                    ->icon('heroicon-s-eye')
                    ->action(function ($record, SupplierPortalService $supplierPortalService) {
                        $supplierPortalService->changeSupplierPortalStatusToRead($record);
                        $viewUrl = SupplierPortalResource::getUrl('view', ['record' => $record]);

                        return redirect($viewUrl);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SupplierPortalFilesRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierPortals::route('/'),
            // 'create' => Pages\CreateSupplierPortal::route('/create'),
            'view' => Pages\ViewSupplierPortal::route('/{record}'),
            // 'edit' => Pages\EditSupplierPortal::route('/{record}/edit'),

            // Respuesta de proveedor
            'response.create' => \App\Filament\Resources\SupplierIssueResponseResource\Pages\CreateSupplierIssueResponse::route('/{supplier_issue}/supplier-issue-responses/create'),
            'response.view' => \App\filament\resources\SupplierIssueResponseResource\Pages\ViewSupplierIssueResponse::route('/{supplier_issue}/supplier-issue-response/{record}'),
        ];
    }
}
