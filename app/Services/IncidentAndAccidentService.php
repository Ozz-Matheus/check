<?php

namespace App\Services;

use App\Models\IAndAEventType;
use App\Models\IncidentAndAccident;
use App\Models\SubProcess;
use Illuminate\Support\Facades\DB;

class IncidentAndAccidentService
{
    public function generateCode($eventTypeId, $subProcessId): string
    {
        return DB::transaction(function () use ($eventTypeId, $subProcessId) {

            $type = IAndAEventType::lockForUpdate()->findOrFail($eventTypeId);
            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);

            $count = IncidentAndAccident::where('event_type_id', $eventTypeId)
                ->where('sub_process_id', $subProcessId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "{$type->acronym}-{$subProcess->acronym}-{$consecutive}";
        });
    }
}
