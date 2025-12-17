<?php

namespace App\Services;

use App\Models\DocVersion;
use App\Models\Status;
use App\Notifications\VersionStatusNotice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * Servicio de cambio de estado de versiones
 */
class VersionStatusService
{
    protected VersionService $versionService;

    public function __construct(VersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    // Estados de las versiones.

    public function pending(DocVersion $docVersion): void
    {
        $status = Status::byContextAndTitle('doc', 'pending');
        $messageBody = __('Pending version ').$docVersion->version;

        $this->updateVersionStatus($docVersion, $status, $messageBody);
    }

    public function rejected(DocVersion $docVersion): void
    {
        $status = Status::byContextAndTitle('doc', 'rejected');
        $messageBody = __('Rejected version ').$docVersion->version;

        $this->updateVersionStatus($docVersion, $status, $messageBody);
    }

    public function approved(DocVersion $docVersion): void
    {

        $status = Status::byContextAndTitle('doc', 'approved');

        $messageBody = __('Approved version ').$docVersion->version;

        $data = [
            'status_id' => $status->id,
            'doc_id' => $docVersion->doc_id,
            'created_by_id' => $docVersion->created_by_id,
        ];

        $validated = $this->versionService->validatedData($data, ['status_id', 'created_by_id']);

        DB::transaction(fn () => $docVersion->update($validated));

        $this->notifyStatusChange($docVersion, $status, $messageBody);
    }

    public function restore(DocVersion $docVersion): void
    {
        $messageBody = __('Restored version ').$docVersion->version;

        $lastFileId = $docVersion->latest()->first()->id;

        $sha256_hash = hash('sha256', $docVersion->path.$lastFileId);

        $data = [
            'doc_id' => $docVersion->doc_id,
            'sub_process_id' => $docVersion->doc->sub_process_id,
            'comment' => Str::limit(strip_tags(request()->query('comment', $docVersion->comment)), 255),
            'sha256_hash' => $sha256_hash,
        ];

        $validated = $this->versionService->validatedData($data);

        $originalFile = $docVersion->file;

        // Recuperamos los IDs de los usuarios que votaban en la versión original
        $originalLeadersIds = $docVersion->leads()->pluck('users.id')->toArray();

        DB::transaction(function () use ($validated, $originalFile, $originalLeadersIds) {

            $newVersion = DocVersion::create($validated);

            $newVersion->file()->create([
                'name' => $originalFile->name,
                'path' => $originalFile->path,
                'mime_type' => $originalFile->mime_type,
                'size' => $originalFile->size,
            ]);

            // Si existían líderes, los asociamos a la nueva versión restaurada
            if (! empty($originalLeadersIds)) {

                $pendingStatus = Status::byContextAndTitle('doc', 'pending');

                // Preparamos los datos pivot (estado pendiente y comentario por defecto)
                $newVersion->leads()->attach($originalLeadersIds, [
                    'status_id' => $pendingStatus->id,
                    'comment' => __('Restored version pending decision'),
                ]);
            }
        });

        $status = Status::findOrFail($validated['status_id']);
        $this->notifyStatusChange($docVersion, $status, $messageBody);
    }

    // Métodos auxiliares privados.

    private function updateVersionStatus(DocVersion $docVersion, Status $status, ?string $messageBody = null): void
    {

        $docVersion->update([
            'status_id' => $status->id,
        ]);

        $this->notifyStatusChange($docVersion, $status, $messageBody ?? '');
    }

    protected function notifyStatusChange(DocVersion $docVersion, Status $status, string $messageBody): void
    {
        $user = auth()->user();

        $leaders = $user->getLeadersToSubProcess($docVersion->doc->sub_process_id);

        $notifiables = collect([$user, $docVersion->createdBy])->merge($leaders ?? [])
            ->filter()
            ->unique('id');

        Notification::send($notifiables, new VersionStatusNotice($docVersion, $status, $messageBody));

        session()->flash('version_status', [
            'status_title' => $status->title,
        ]);
    }
}
