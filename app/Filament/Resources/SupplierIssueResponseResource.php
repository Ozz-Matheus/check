<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierIssueResponseResource\Pages;
use App\Models\SupplierIssueResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierIssueResponseResource extends Resource
{
    protected static ?string $model = SupplierIssueResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('supplier_response')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('supplier_actions')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('response_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplierIssue.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('response_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
}
