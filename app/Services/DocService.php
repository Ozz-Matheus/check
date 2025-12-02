<?php

namespace App\Services;

use App\Models\Doc;
use App\Models\DocType;
use App\Models\Headquarter;
use App\Models\SubProcess;
use Illuminate\Support\Facades\DB;

class DocService
{
    /**
     * Generate document code.
     */
    public function generateCode($docTypeId, $subProcessId, $headquarterId): string
    {

        $headquarterId = $headquarterId ?? auth()->user()->headquarter_id;

        return DB::transaction(function () use ($docTypeId, $subProcessId, $headquarterId) {
            $type = DocType::lockForUpdate()->findOrFail($docTypeId);
            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);
            $headquarter = Headquarter::lockForUpdate()->findOrFail($headquarterId);

            $count = Doc::where('doc_type_id', $docTypeId)
                ->where('sub_process_id', $subProcessId)
                ->where('headquarter_id', $headquarterId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "{$type->acronym}-{$subProcess->acronym}-{$consecutive}-{$headquarter->acronym}";
        });
    }
}
