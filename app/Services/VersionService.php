<?php

namespace App\Services;

use App\Models\Doc;
use App\Models\DocVersion;
use App\Models\Status;
use App\Traits\HasVersioning;

/**
 * Servicio para las versiones
 */
class VersionService
{
    use HasVersioning;

    public function validatedData(array $data, array $preserve = []): array
    {
        $user = auth()->user();
        $doc = Doc::with('subProcess')->findOrFail($data['doc_id']);

        $hasApprovalAccess = $user->canApproveAndReject($doc->sub_process_id ?? null);
        $statusApproved = Status::byContextAndTitle('doc', 'approved');
        $statusDraft = Status::byContextAndTitle('doc', 'draft');

        $lastVersion = DocVersion::where('doc_id', $data['doc_id'])
            ->orderByDesc('version')
            ->first();

        $newVersion = $this->calculateNewVersion($lastVersion, $hasApprovalAccess);

        return array_merge($data, [
            'status_id' => in_array('status_id', $preserve) ? ($data['status_id'] ?? null) : ($hasApprovalAccess ? $statusApproved->id : $statusDraft->id),
            'version' => in_array('version', $preserve) ? ($data['version'] ?? null) : $newVersion,
            'created_by_id' => in_array('created_by_id', $preserve) ? ($data['created_by_id'] ?? null) : $user->id,
            'decided_by_id' => in_array('decided_by_id', $preserve) ? ($data['decided_by_id'] ?? null) : ($hasApprovalAccess ? auth()->id() : null),
            'decided_at' => in_array('decided_at', $preserve) ? ($data['decided_at'] ?? null) : ($hasApprovalAccess ? now() : null),
        ]);
    }
}
