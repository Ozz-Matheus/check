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

        $targetStatus = isset($data['status_id']) == $statusApproved->id ? 'approved' : null;

        $newVersion = $this->calculateNewVersion($lastVersion?->version, $hasApprovalAccess, $targetStatus);

        if ($hasApprovalAccess) {
            $doc->expirationDateAssignment();
        }

        return array_merge($data, [
            'version' => $newVersion,
            'status_id' => in_array('status_id', $preserve) ? ($data['status_id'] ?? null) : ($statusDraft->id),
            'created_by_id' => in_array('created_by_id', $preserve) ? ($data['created_by_id'] ?? null) : $user->id,
        ]);
    }
}
