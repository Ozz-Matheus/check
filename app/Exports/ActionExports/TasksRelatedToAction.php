<?php

namespace App\Exports\ActionExports;

use App\Models\ActionTask;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TasksRelatedToAction implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected array $actionIds;

    public function __construct(array $actionIds)
    {
        $this->actionIds = $actionIds;
    }

    public function title(): string
    {
        return '📋 Tareas relacionadas';
    }

    public function collection(): Collection
    {
        return ActionTask::query()
            ->whereIn('action_id', $this->actionIds)
            ->get();
    }

    public function map($task): array
    {
        return [
            $task->action?->title,
            $task->title,
            $task->detail,
            optional($task->responsibleBy)->name,
            $task->status->label ?? __('Stateless'),
            $task->start_date,
            $task->limit_date,
            $task->real_start_date ?? __('Unstarted'),
            $task->real_closing_date ?? __('Unclosed'),
            $task->cancellation_date,
            $task->finished,
            $task->extemporaneous_reason ?? __('Empty'),
            $task->reason_for_cancellation ?? __('Empty'),
            $task->created_at?->format('Y-m-d H:i'),
            $task->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            __('Action'),
            __('Title'),
            __('Detail'),
            __('Responsible'),
            __('Status'),
            __('Start date'),
            __('Limit date'),
            __('Real start date'),
            __('Real closing date'),
            __('Cancellation date'),
            __('Finished'),
            __('Extemporaneous reason'),
            __('reason_for_cancellation'),
            __('Created at'),
            __('Updated at'),
        ];
    }
}
