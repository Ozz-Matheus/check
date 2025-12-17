<?php

namespace App\Filament\Resources;

use App\Exports\DocExports\VersionExport;
use App\Models\Doc;
use App\Models\DocVersion;
use App\Models\Status;
use App\Models\SubProcess;
use App\Traits\HasStandardFileUpload;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DocVersionResource extends Resource
{
    use HasStandardFileUpload;

    protected static ?string $model = DocVersion::class;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getModelLabel(): string
    {
        return __('Doc Version');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Doc Versions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('File data'))
                    ->schema([
                        static::baseFileUpload('path')
                            ->label(__('File'))
                            ->directory('docs/versions')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('leads')
                            ->label(__('leads'))
                            ->required()
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn (Component $livewire) => static::getSubProcessLeadersOptions($livewire)),
                        TextArea::make('comment')
                            ->label(__('Comment'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file.name')
                    ->label(__('Name'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->file->name)
                    ->copyable()
                    ->copyMessage(__('Name copied'))
                    ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME)))
                    ->searchable(),
                Tables\Columns\TextColumn::make('file.readable_mime_type')
                    ->label(__('Type')),
                Tables\Columns\TextColumn::make('file.readable_size')
                    ->label(__('Size'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName())
                    ->icon(fn ($record) => $record->status->iconName()),
                Tables\Columns\TextColumn::make('version')
                    ->label(__('Version'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('Comment'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->comment)
                    ->copyable()
                    ->copyMessage(__('Comment copied'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sha256_hash')
                    ->label(__('Sha256_hash'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'label',
                        modifyQueryUsing: fn ($query) => $query->where('context', 'doc')->where('title', '!=', 'restore')->orderBy('id', 'asc')
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->filtersTriggerAction(
                fn ($action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->actions([
                Action::make('file')
                    ->label(__('View'))
                    ->icon('heroicon-s-eye')
                    ->color('gray')
                    ->url(fn ($record) => route('filament.dashboard.pages.file-viewer', ['file' => $record->file]))
                    ->visible(
                        fn ($record) => auth()->user()->canAccessSubProcess($record->doc->sub_process_id)
                    ),
                ActionGroup::make([
                    // PENDING
                    Action::make('pending')
                        ->label(fn ($record) => Status::labelFromTitle('pending') ?? 'Pending')
                        ->icon(fn ($record) => Status::iconFromTitle('pending') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('pending') ?? 'gray')
                        ->requiresConfirmation()
                        ->action(function ($record, array $data) {
                            redirect(DocResource::getUrl('versions.pending', [
                                'doc' => $record->doc_id,
                                'version' => $record->id,
                            ]));
                        })
                        ->visible(function ($record) {
                            return auth()->user()->canPending($record)
                                && $record->status_id === Status::byContextAndTitle('doc', 'draft')?->id
                                && $record->isLatestVersion();
                        }),

                    // RESTORE
                    Action::make('restore')
                        ->label(fn ($record) => Status::labelFromTitle('restore') ?? 'Restore')
                        ->icon(fn ($record) => Status::iconFromTitle('restore') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('restore') ?? 'gray')
                        ->authorize(fn ($record) => auth()->user()->can('create_doc::version', $record))
                        ->form([
                            Textarea::make('comment')
                                ->label(__('Confirm the restoration'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder(__('¿Reason for restore?')),
                        ])
                        ->action(function ($record, array $data) {
                            redirect(DocResource::getUrl('versions.restore', [
                                'doc' => $record->doc_id,
                                'version' => $record->id,
                                'comment' => $data['comment'],
                            ]));
                        })
                        ->visible(
                            fn ($record) => $record->id !== DocVersion::where('doc_id', $record->doc_id)
                                ->orderByDesc('version')
                                ->first()?->id
                        ),
                    DeleteAction::make()
                        ->visible(function ($record) {
                            $user = auth()->user();

                            return $user && $user->hasRole('super_admin');
                        }),
                ])->color('primary')->link()->label(false)->tooltip('Actions'),
            ])
            ->bulkActions([
                BulkAction::make('export')
                    ->label(__('Export base'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($records) => Excel::download(
                        new VersionExport($records->pluck('id')->toArray()),
                        'versions_'.now()->format('Y_m_d_His').'.xlsx'
                    ))->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getSubProcessLeadersOptions(Component $livewire): Collection|array
    {
        if (isset($livewire->docModel) && $livewire->docModel instanceof Doc) {
            return SubProcess::find($livewire->docModel->sub_process_id)
                ?->leaders()
                ->pluck('users.name', 'users.id')
                ?? [];
        }
    }
}
