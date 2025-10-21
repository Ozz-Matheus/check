<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierIssueResponseResource\Pages;
use App\Filament\Resources\SupplierIssueResponseResource\RelationManagers\SupplierIssueResponseFilesRelationManager;
use App\Models\SupplierIssueResponse;
use App\Traits\HasStandardFileUpload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class SupplierIssueResponseResource extends Resource
{
    use HasStandardFileUpload;

    protected static ?string $model = SupplierIssueResponse::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Suplier response data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('supplier_response')
                            ->label(__('Supplier response'))
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('supplier_actions')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('response_date')
                            ->label(__('Response date'))
                            ->native(false)
                            ->visibleOn('view'),
                        Forms\Components\TextInput::make('effectiveness')
                            ->label(__('Effectiveness'))
                            ->visible(fn ($record) => filled($record?->effectiveness)),
                        Forms\Components\Textarea::make('evaluation_comment')
                            ->label(__('Evaluation comment'))
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn ($record) => filled($record?->evaluation_comment)),
                        Forms\Components\DatePicker::make('evaluation_date')
                            ->label(__('Evaluation date'))
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->evaluation_date)),
                        static::baseFileUpload('path')
                            ->label(__('Support files'))
                            ->directory('supplier-issues-responses/files')
                            ->multiple()
                            ->required()
                            ->columnSpanFull()
                            ->visibleOn('create'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SupplierIssueResponseFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierIssueResponses::route('/'),
            'create' => Pages\CreateSupplierIssueResponse::route('/create'),
            'edit' => Pages\EditSupplierIssueResponse::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
