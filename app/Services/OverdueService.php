<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class OverdueService
{
    public static function markAsOverdue(string $modelClass, string $dateColumn = 'limit_date', string $statusColumn = 'status_id'): int
    {
        $today = Carbon::today()->toDateString();

        $statusService = new StatusService;
        $statusIds = $statusService->getActionAndTaskStatuses();

        return $modelClass::whereDate($dateColumn, '<', $today)
            ->whereIn($statusColumn, [
                $statusIds['pending'],
                $statusIds['in_execution'],
            ])
            ->update([$statusColumn => $statusIds['overdue']]);
    }
}
