<?php

namespace App\Services;

use App\Models\Status;

class StatusService
{
    public function getActionAndTaskStatuses(): array
    {
        return [
            'pending' => Status::byContextAndTitle('action_and_task', 'pending')?->id,
            'in_execution' => Status::byContextAndTitle('action_and_task', 'in_execution')?->id,
            'completed' => Status::byContextAndTitle('action_and_task', 'completed')?->id,
            'overdue' => Status::byContextAndTitle('action_and_task', 'overdue')?->id,
            'extemporaneous' => Status::byContextAndTitle('action_and_task', 'extemporaneous')?->id,
            'canceled' => Status::byContextAndTitle('action_and_task', 'canceled')?->id,
        ];
    }
}
