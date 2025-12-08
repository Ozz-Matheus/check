<?php

namespace App\Filament\Pages;

use App\Filament\Resources\DocResource;
use App\Models\Doc;
use App\Models\DocVersion;
use App\Models\File;
use App\Models\Status;
use App\Models\UserVersionDecision;
use App\Support\AppNotifier;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class FileViewer extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.file-viewer';

    public ?File $file = null;

    public ?Doc $doc = null;

    public function getTitle(): string
    {
        return __('File Viewer');
    }

    public function mount(): void
    {
        $this->file = File::findOrFail(request('file'));

        if ($this->file->fileable_type === DocVersion::class) {

            $docVersion = DocVersion::findOrFail($this->file->fileable_id);

            $this->doc = Doc::findOrFail($docVersion->doc_id);

            $user = auth()->user();

            if ($this->doc->confidential) {

                $hasAccess = DB::table('docs_has_confidential_users')
                    ->where('doc_id', $this->doc->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (! $hasAccess && ! $user->canAccessSubProcess($this->doc->sub_process_id)) {

                    AppNotifier::error(
                        __('Document'),
                        __('You do not have permission to view this document.'),
                        true
                    );
                    abort(403);
                }
            }
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                UserVersionDecision::query()
                    // Filtramos solo si el archivo actual es una versión
                    ->where('version_id', $this->file->fileable_type === DocVersion::class ? $this->file->fileable_id : 0)
            )
            ->heading(__('Decision History')) // Título de la tabla
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status.label')
                    ->label(__('Decision'))
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName())
                    ->icon(fn ($record) => $record->status->iconName()),

                TextColumn::make('comment')
                    ->label(__('Comment'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->comment),

                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->since()
                    ->dateTooltip()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->sortable()
                    ->since()
                    ->dateTooltip()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                // APPROVED
                ActionTable::make('approved')
                    ->label(Status::labelFromTitle('approved') ?? 'Approved')
                    ->icon(Status::iconFromTitle('approved') ?? 'heroicon-o-information-circle')
                    ->color(Status::colorFromTitle('approved') ?? 'gray')
                    ->button()
                    ->requiresConfirmation()
                    ->visible(fn (UserVersionDecision $record) => $this->canVote($record))
                    ->action(fn (UserVersionDecision $record) => $this->updateDecision($record, 'approved', __('Approved version'))),
                // REJECTED
                ActionTable::make('rejected')
                    ->label(fn ($record) => Status::labelFromTitle('rejected') ?? 'Rejected')
                    ->icon(fn ($record) => Status::iconFromTitle('rejected') ?? 'heroicon-o-information-circle')
                    ->color(fn ($record) => Status::colorFromTitle('rejected') ?? 'gray')
                    ->button()
                    ->form([
                        Textarea::make('comment')
                            ->label(__('Confirm Rejection'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('¿Reason for rejected?')),
                    ])
                    ->visible(fn (UserVersionDecision $record) => $this->canVote($record))
                    ->action(fn (UserVersionDecision $record, array $data) => $this->updateDecision($record, 'rejected', $data['comment'])),
                DeleteAction::make()
                    ->visible(function ($record) {
                        $user = auth()->user();

                        return $user && $user->hasRole('super_admin');
                    }),
            ])
            ->paginated(false); // Opcional: Desactivar paginación si son pocos registros
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => $this->doc ? DocResource::getUrl('versions.index', ['doc' => $this->doc]) : back()->getTargetUrl()) // Pequeña mejora para evitar error si $this->doc es null
                ->button()
                ->color('gray'),

        ];
    }

    public function getBreadcrumbs(): array
    {
        // Validación extra por si se visualiza un archivo que no es DocVersion
        if (! $this->doc) {
            return [];
        }

        return [
            DocResource::getUrl('index') => __('Documents'),
            DocResource::getUrl('versions.index', ['doc' => $this->doc->id]) => __('Versions'),
            false => __('File Viewer'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->file->name;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function canVote(UserVersionDecision $record): bool
    {
        // 1️⃣ Regla de Propiedad: El usuario solo puede votar sobre SU propio registro.
        if ($record->user_id !== auth()->id()) {
            return false;
        }

        // 2️⃣ Regla de Estado: Solo si el estado es 'pending'
        $pendingStatusId = Status::byContextAndTitle('doc', 'pending')?->id;

        return $record->status_id == $pendingStatusId;
    }

    protected function updateDecision(UserVersionDecision $record, string $statusTitle, ?string $comment = null)
    {
        // 1️⃣ Buscamos el ID del estado basado en el contexto 'doc' y el título (approved/rejected)
        $status = Status::byContextAndTitle('doc', $statusTitle);

        // 2️⃣ Actualizamos el registro existente.
        $record->update([
            'status_id' => $status->id,
            'comment' => $comment,
        ]);

        // 3️⃣ Notificación
        AppNotifier::success(__('Decision saved successfully'));

        // 4️⃣ Redirección
        return redirect()->back();
    }
}
