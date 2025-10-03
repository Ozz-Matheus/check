<?php

namespace App\Filament\Resources;

use App\Exports\DocExport;
use App\Filament\Resources\DocResource\Pages;
use App\Models\Doc;
use App\Models\DocType;
use App\Models\Status;
use App\Models\User;
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
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class DocResource extends Resource
{
    protected static ?string $model = Doc::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $navigationLabel = null;

    public static function getModelLabel(): string
    {
        return __('models.doc.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('models.doc.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('Documents');
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $docTypeFormat = fn (Get $get) => (int) $get('doc_type_id') === DocType::where('name', 'format')->value('id');

        return $form
            ->schema([
                Forms\Components\Section::make(__('Doc data'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255)
                            ->unique(),
                        Forms\Components\Select::make('doc_type_id')
                            ->label(__('Doc type'))
                            ->relationship('type', 'label')
                            ->afterStateUpdated(function (Set $set) {
                                $set('storage_method_id', null);
                                $set('recovery_method_id', null);
                                $set('disposition_method_id', null);
                            })
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
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
                        Forms\Components\Select::make('storage_method_id')
                            ->label(__('Storage method'))
                            ->relationship('storageMethod', 'label')
                            ->afterStateUpdated(function (Set $set) {
                                $set('recovery_method_id', null);
                                $set('disposition_method_id', null);
                            })
                            ->reactive()
                            ->native(false)
                            ->visible($docTypeFormat)
                            ->required($docTypeFormat),
                        Forms\Components\Select::make('recovery_method_id')
                            ->label(__('Recovery method'))
                            ->relationship(
                                name: 'recoveryMethod',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('storage_id', $get('storage_method_id'))
                            )
                            ->native(false)
                            ->preload()
                            ->visible($docTypeFormat)
                            ->required($docTypeFormat),
                        Forms\Components\Select::make('disposition_method_id')
                            ->label(__('Disposition method'))
                            ->relationship(
                                name: 'dispositionMethod',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query, Get $get) => $query->where('storage_id', $get('storage_method_id'))
                            )
                            ->native(false)
                            ->preload()
                            ->visible($docTypeFormat)
                            ->required($docTypeFormat),
                        Forms\Components\Fieldset::make(__('Doc restriction'))
                            ->schema([
                                Forms\Components\Toggle::make('display_restriction')
                                    ->label(__('Display restriction'))
                                    ->inline(false)
                                    ->afterStateUpdated(fn (Set $set) => $set('accessToAdditionalUsers', null))
                                    ->columnSpanFull()
                                    ->reactive(),
                                Forms\Components\Select::make('accessToAdditionalUsers')
                                    ->label(__('Access to additional users'))
                                    ->relationship('accessToAdditionalUsers', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Get $get) => $get('display_restriction') === true)
                                    ->required(fn (Get $get) => $get('display_restriction') === true),
                            ]),
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
                Tables\Columns\TextColumn::make('type.label')
                    ->label(__('Doc type')),
                Tables\Columns\TextColumn::make('process.title')
                    ->label(__('Process')),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->label(__('Sub process')),
                Tables\Columns\TextColumn::make('latestVersion.status.label')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($record) => $record->latestVersion?->status?->colorName() ?? 'gray')
                    ->icon(fn ($record) => $record->latestVersion?->status?->iconName() ?? 'heroicon-o-information-circle')
                    ->default('-'),
                Tables\Columns\TextColumn::make('latestVersion.version')
                    ->label(__('Version'))
                    ->sortable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('central_expiration_date')
                    ->label(__('Central expiration date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiration_status')
                    ->label(__('Expiration status'))
                    ->badge()
                    ->state(fn (Doc $record): string => $record->is_expired ? __('Expired') : __('Current'))
                    ->color(fn (string $state): string => $state === __('Expired') ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('display_restriction')
                    ->label(__('Display restriction'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => (bool) $state ? __('Private') : __('Public'))
                    ->color(fn ($state) => (bool) $state ? 'warning' : 'success'),
                Tables\Columns\TextColumn::make('storageMethod.label')
                    ->label(__('Storage method'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('recoveryMethod.title')
                    ->label('Recovery method')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('dispositionMethod.title')
                    ->label('Disposition method')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('doc_type_id')
                    ->label(__('Doc type'))
                    ->relationship('type', 'label')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('process_id')
                    ->label(__('Process'))
                    ->relationship('process', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sub_process_id')
                    ->label(__('Sub Process'))
                    ->relationship('subProcess', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->options(
                        Status::where('context', 'doc')->where('title', '!=', 'restore')->orderBy('id', 'asc')->pluck('label', 'id')
                    )
                    ->multiple()
                    ->query(function (Builder $query, array $data): Builder {
                        $values = $data['values'];

                        if (empty($values)) {
                            return $query;
                        }

                        return $query->whereHas('latestVersion', fn (Builder $query) => $query->whereIn('status_id', $values));
                    }),
                SelectFilter::make('expiration_status')
                    ->label(__('Expiration status'))
                    ->options([
                        1 => __('Expired'),
                        0 => __('Current'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'];

                        if ($value === null || ! in_array((int) $value, [0, 1], true)) {
                            return $query;
                        }

                        return (int) $value === 1
                            ? $query->whereNotNull('central_expiration_date')->where('central_expiration_date', '<', today())
                            : $query->where(fn (Builder $q) => $q->whereNull('central_expiration_date')->orWhere('central_expiration_date', '>=', today()));
                    })
                    ->native(false),
                SelectFilter::make('expiration_soon')
                    ->label(__('Expiring soon'))
                    ->options([
                        10 => 'Expiring in 10 days',
                        30 => 'Expiring in 30 days',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $days = $data['value'] ?? null;

                        return $days
                            ? $query->whereBetween('central_expiration_date', [today(), today()->addDays((int) $days)])
                            : $query;
                    })
                    ->native(false),
                SelectFilter::make('display_restriction')
                    ->label(__('Display restriction'))
                    ->options([
                        1 => __('Private'),
                        0 => __('Public'),
                    ])
                    ->native(false),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->filtersFormColumns(2)
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
                    ->visible(function ($record) {
                        if (! $record->display_restriction) {
                            return true;
                        }

                        $user = auth()->user();

                        if ($user->canAccessSubProcess($record->sub_process_id)) {
                            return true;
                        }

                        return $record->accessToAdditionalUsers()->where('user_id', $user->id)->exists();
                    })
                    ->disabled(fn ($record) => ! $record->hasApprovedVersion() || $record->is_expired)
                    ->extraAttributes(fn ($record) => [
                        'download' => $record->latestApprovedVersion?->file->name,
                        'style' => $record->hasApprovedVersion() && ! $record->is_expired
                            ? ''
                            : 'opacity: 0.3; cursor: not-allowed;',

                    ]),

                ActionGroup::make([
                    Action::make('update_additional_users')
                        ->label(__('Update additional users'))
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->form(function ($record) {

                            // Helpers para no repetir tanto fn(Get $get) / fn(Set $set)
                            $isPrivate = fn (Get $get): bool => $get('display_restriction') === true;
                            $resetAccess = fn (Set $set) => $set('users', null);

                            return [
                                Forms\Components\Toggle::make('display_restriction')
                                    ->label(__('Display restriction'))
                                    ->inline(false)
                                    ->default($record->display_restriction)
                                    ->afterStateUpdated($resetAccess)
                                    ->columnSpanFull()
                                    ->reactive(),

                                Forms\Components\Select::make('users')
                                    ->label(__('Access to additional users'))
                                    ->options(User::pluck('name', 'id'))
                                    ->default($record?->accessToAdditionalUsers?->pluck('id')->toArray())
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->visible($isPrivate)
                                    ->required($isPrivate),
                            ];
                        })
                        ->authorize(fn ($record): bool => auth()->id() === $record->subProcess?->leader?->id)
                        ->action(function ($record, array $data) {

                            session([
                                'doc_edit_payload' => [
                                    'display_restriction' => $data['display_restriction'],
                                    'users' => $data['users'] ?? null,
                                ],
                            ]);

                            redirect(DocResource::getUrl('access', [
                                'record' => $record,
                            ]));
                        }),
                    Action::make('reinstate_doc')
                        ->label(__('Reinstate Document'))
                        ->icon('heroicon-o-document-check')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->authorize(fn ($record): bool => auth()->id() === $record->subProcess?->leader?->id)
                        ->visible(fn ($record) => $record->is_about_to_expire || $record->is_expired)
                        ->action(function ($record) {
                            $record->reactivateDoc();
                        }),
                    DeleteAction::make()
                        ->visible(fn ($record): bool => auth()->user()?->can('delete', $record)),

                    // Tables\Actions\EditAction::make(),

                ])->color('primary')->link()->label(false)->tooltip('Actions'),

            ])
            ->bulkActions([
                BulkAction::make('export')
                    ->label(__('Export selected'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($records) => Excel::download(
                        new DocExport($records->pluck('id')->toArray()),
                        'docs_'.now()->format('Y_m_d_His').'.xlsx'
                    )),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocs::route('/'),
            'create' => Pages\CreateDoc::route('/create'),
            'access' => Pages\UpdateAdditionalUsers::route('/{record}/access'),
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
