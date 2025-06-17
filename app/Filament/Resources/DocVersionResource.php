<?php

namespace App\Filament\Resources;

use App\Exports\VersionExport;
use App\Models\DocVersion;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DocVersionResource extends Resource
{
    protected static ?string $model = DocVersion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('File Data'))
                    ->schema([
                        Forms\Components\FileUpload::make('path')
                            ->label(__('File'))
                            ->storeFileNamesIn('name')
                            ->disk('public')
                            ->directory('docs/versions')
                            ->required()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
                            ])
                            ->maxSize(10240) // en KB, 10MB ejemplo
                            ->helperText('Allowed types: PDF, DOC, DOCX, XLS, XLSX (max. 10MB)')
                            ->columnSpanFull(),
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
                    ->label(__('Title'))
                    ->formatStateUsing(fn (string $state) => ucfirst(pathinfo($state, PATHINFO_FILENAME)))
                    ->searchable(),
                Tables\Columns\TextColumn::make('file.mime_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state) => strtoupper(Str::after($state, '/'))),
                Tables\Columns\TextColumn::make('file.readable_size')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('status.label')
                    ->label(__('Status'))
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('version')
                    ->label(__('Version'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('Comment'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->comment)
                    ->searchable(),
                Tables\Columns\TextColumn::make('change_reason')
                    ->label(__('Reason for change'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->change_reason)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('decidedBy.name')
                    ->label(__('Decided By'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('decision_at')
                    ->label(__('Decision at'))
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sha256_hash')
                    ->label(__('Sha256_hash'))
                    ->searchable()
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
            ])
            ->actions([
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
                                && $record->status_id === 1
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

                    // APPROVED
                    Action::make('approved')
                        ->label(fn ($record) => Status::labelFromTitle('approved') ?? 'Approved')
                        ->icon(fn ($record) => Status::iconFromTitle('approved') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('approved') ?? 'gray')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            redirect(DocResource::getUrl('versions.approved', [
                                'doc' => $record->doc_id,
                                'version' => $record->id,
                            ]));
                        })
                        ->visible(function ($record) {
                            return auth()->user()->canApproveAndReject(
                                $record->doc->sub_process_id ?? null
                            ) && $record->status_id === 2 && $record->isLatestVersion();
                        }),

                    // REJECTED
                    Action::make('rejected')
                        ->label(fn ($record) => Status::labelFromTitle('rejected') ?? 'Rejected')
                        ->icon(fn ($record) => Status::iconFromTitle('rejected') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('rejected') ?? 'gray')
                        ->form([
                            Textarea::make('change_reason')
                                ->label(__('Confirm Rejection'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder(__('¿Reason for rejected?')),
                        ])
                        ->action(function ($record, array $data) {
                            redirect(DocResource::getUrl('versions.rejected', [
                                'doc' => $record->doc_id,
                                'version' => $record->id,
                                'change_reason' => $data['change_reason'],
                            ]));
                        })
                        ->visible(function ($record) {
                            return auth()->user()->canApproveAndReject(
                                $record->doc->sub_process_id ?? null
                            ) && $record->status_id === 2 && $record->isLatestVersion();
                        }),

                    Action::make('file')
                        ->label(__('Download'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('primary')
                        ->url(fn ($record) => $record->file?->url())
                        ->openUrlInNewTab(false)
                        ->extraAttributes(fn ($record) => [
                            'download' => $record->file?->name,
                        ])
                        ->visible(
                            fn ($record) => auth()->user()->canAccessSubProcess($record->doc->sub_process_id)
                        ),

                    DeleteAction::make()
                        ->visible(function ($record) {
                            $user = auth()->user();

                            return $user && $user->hasRole('super_admin');
                        }),

                    // Tables\Actions\EditAction::make(),
                ])->color('primary')->link()->label(false)->tooltip('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                    BulkAction::make('export')
                        ->label(__('Export selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new VersionExport($records->pluck('id')->toArray()),
                            'versions_'.now()->format('Y_m_d_His').'.xlsx'
                        )),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
