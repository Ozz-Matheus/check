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
        $changeReason = 'Pending from version '.$docVersion->version;

        $this->updateVersionStatus($docVersion, $status, $changeReason);
    }

    public function rejected(DocVersion $docVersion): void
    {
        $status = Status::byContextAndTitle('doc', 'rejected');
        $reasonMessage = 'Rejected from version '.$docVersion->version;
        $changeReason = Str::limit(strip_tags(request()->query('change_reason', $reasonMessage)), 255);
        $extra = [
            'decided_by_id' => auth()->id(),
            'decided_at' => now(),
        ];

        $this->updateVersionStatus($docVersion, $status, $changeReason, $extra);
    }

    public function approved(DocVersion $docVersion): void
    {
        $data = [
            'doc_id' => $docVersion->doc_id,
            'change_reason' => 'Approved from version '.$docVersion->version,
            'created_by_id' => $docVersion->created_by_id,
        ];

        $validated = $this->versionService->validatedData($data, ['created_by_id']);

        DB::transaction(fn () => $docVersion->update($validated));

        $status = Status::byContextAndTitle('doc', 'approved');
        $this->notifyStatusChange($docVersion, $status, $data['change_reason']);
    }

    public function restore(DocVersion $docVersion): void
    {
        $lastFileId = $docVersion->latest()->first()->id;

        $sha256_hash = hash('sha256', $docVersion->path.$lastFileId);

        $data = [
            'doc_id' => $docVersion->doc_id,
            'sub_process_id' => $docVersion->doc->sub_process_id,
            'comment' => Str::limit(strip_tags(request()->query('comment', $docVersion->comment)), 255),
            'change_reason' => 'Restored from version '.$docVersion->version,
            'sha256_hash' => $sha256_hash,
        ];

        $validated = $this->versionService->validatedData($data);

        $originalFile = $docVersion->file;

        DB::transaction(function () use ($validated, $originalFile) {

            $newVersion = DocVersion::create($validated);

            $newVersion->file()->create([
                'name' => $originalFile->name,
                'path' => $originalFile->path,
                'mime_type' => $originalFile->mime_type,
                'size' => $originalFile->size,
            ]);
        });

        $status = Status::findOrFail($validated['status_id']);
        $this->notifyStatusChange($docVersion, $status, $data['change_reason']);
    }

    // Métodos auxiliares privados.

    private function updateVersionStatus(DocVersion $docVersion, Status $status, ?string $changeReason = null, array $extra = []): void
    {

        $docVersion->update(array_merge([
            'status_id' => $status->id,
            'change_reason' => $changeReason,
        ], $extra));

        $this->notifyStatusChange($docVersion, $status, $changeReason ?? '');
    }

    protected function notifyStatusChange(DocVersion $docVersion, Status $status, string $message): void
    {
        $user = auth()->user();

        $leader = $user->getLeaderToSubProcess($docVersion->doc->sub_process_id);

        $notifiables = collect([$user, $docVersion->createdBy, $leader])->filter()->unique('id');

        Notification::send($notifiables, new VersionStatusNotice($docVersion, $status, $message));

        session()->flash('version_status', [
            'status_title' => $status->title,
        ]);
    }
}
