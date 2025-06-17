<?php

namespace App\Services;

use App\Models\Doc;
use App\Models\DocType;
use App\Models\SubProcess;
use Illuminate\Support\Facades\DB;

class DocService
{
    /**
     * Generate document code.
     */
    public function generateCode($docTypeId, $subProcessId): string
    {
        return DB::transaction(function () use ($docTypeId, $subProcessId) {

            $type = DocType::lockForUpdate()->findOrFail($docTypeId);
            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);

            $count = Doc::where('doc_type_id', $docTypeId)
                ->where('sub_process_id', $subProcessId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "{$type->acronym}-{$subProcess->acronym}-{$consecutive}";
        });
    }

    /**
     * Get DocType instance.
     */
    public function getDoctype($docTypeId)
    {
        return DocType::with('expirationRule')->find($docTypeId);
    }
}
