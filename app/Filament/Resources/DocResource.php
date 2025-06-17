<?php

namespace App\Filament\Resources;

use App\Exports\DocExport;
use App\Filament\Resources\DocResource\Pages;
use App\Models\Doc;
use App\Models\SubProcess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class DocResource extends Resource
{
    protected static ?string $model = Doc::class;

    protected static ?string $modelLabel = 'Document';

    protected static ?string $pluralModelLabel = 'Documents';

    protected static ?string $navigationLabel = 'Documents';

    protected static ?string $navigationGroup = 'Documents';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Doc Data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255)
                            ->unique(),
                        Forms\Components\Select::make('doc_type_id')
                            ->relationship('type', 'title')
                            ->label(__('Doc type'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->label(__('Process'))
                            ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->label(__('Sub process'))
                            ->options(
                                fn (Get $get): Collection => SubProcess::query()
                                    ->where('process_id', $get('process_id'))
                                    ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('doc_ending_id')
                            ->relationship('ending', 'label')
                            ->label(__('Final disposition'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification_code')
                    ->label(__('Classification code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('type.title')
                    ->label(__('Doc type'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('subprocess.title')
                    ->label(__('Sub process'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('latestVersion.status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->latestVersion?->status->colorName()),
                Tables\Columns\TextColumn::make('latestVersion.version')
                    ->label(__('Version'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('management_review_date')
                    ->label(__('Management review date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('central_expiration_date')
                    ->label(__('Central expiration date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ending.label')
                    ->label(__('Final disposition'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expiration')
                    ->label(__('Expiration state'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => (bool) $state ? 'Expired' : 'Current')
                    ->color(fn ($state) => (bool) $state ? 'danger' : 'success'),
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

            ])->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('doc_type_id')
                    ->relationship('type', 'title')
                    ->label(__('Doc type'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('process_id')
                    ->relationship('process', 'title')
                    ->label(__('Process'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sub_process_id')
                    ->relationship('subprocess', 'title')
                    ->label(__('Sub Process'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('doc_ending_id')
                    ->relationship('ending', 'label')
                    ->label(__('Final disposition'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('expiration')
                    ->label(__('Expiration state'))
                    ->options([
                        1 => 'Expired',
                        0 => 'Current',
                    ])
                    ->searchable()
                    ->preload(),
            ])
            ->actions([

                Action::make('files')
                    ->label(__('Versions'))
                    ->icon('heroicon-o-document')
                    ->color('primary')
                    ->url(
                        fn (Doc $doc): string => DocResource::getUrl('versions.index', ['doc' => $doc->id])
                    )
                    ->visible(
                        fn ($record) => auth()->user()->canAccessSubProcess($record->sub_process_id)
                    ),

                Action::make('latestApprovedVersion')
                    ->label(__('Download'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->url(
                        fn ($record) => $record->approvedVersionUrl()
                    )
                    ->openUrlInNewTab(false)
                    ->disabled(fn ($record) => ! $record->hasApprovedVersion())
                    ->extraAttributes(fn ($record) => [
                        'download' => $record->latestApprovedVersion?->file->name,
                        'style' => $record->hasApprovedVersion()
                            ? ''
                            : 'opacity: 0.3; cursor: not-allowed;',

                    ]),

                ActionGroup::make([

                    DeleteAction::make()
                        ->visible(fn ($record): bool => auth()->user()?->can('delete', $record)),

                    // Tables\Actions\EditAction::make(),

                ])->color('primary')->link()->label(false)->tooltip('Actions'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('export')
                        ->label(__('Export selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new DocExport($records->pluck('id')->toArray()),
                            'docs_'.now()->format('Y_m_d_His').'.xlsx'
                        )),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocs::route('/'),
            'create' => Pages\CreateDoc::route('/create'),
            // 'edit' => Pages\EditDoc::route('/{record}/edit'),
            'versions.index' => \App\Filament\Resources\DocVersionResource\Pages\ListDocVersions::route('/{doc}/versions'),
            'versions.create' => \App\Filament\Resources\DocVersionResource\Pages\CreateDocVersion::route('/{doc}/versions/create'),
            'versions.pending' => \App\Filament\Resources\DocVersionResource\Pages\ChangeVersionStatus::route('/{doc}/versions/pending/{version}'),
            'versions.restore' => \App\Filament\Resources\DocVersionResource\Pages\ChangeVersionStatus::route('/{doc}/versions/restore/{version}'),
            'versions.approved' => \App\Filament\Resources\DocVersionResource\Pages\ChangeVersionStatus::route('/{doc}/versions/approved/{version}'),
            'versions.rejected' => \App\Filament\Resources\DocVersionResource\Pages\ChangeVersionStatus::route('/{doc}/versions/rejected/{version}'),
        ];
    }
}
