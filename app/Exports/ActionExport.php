<?php

namespace App\Exports;

use App\Models\Action;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActionExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $actionIds;

    public function __construct(array $actionIds)
    {
        $this->actionIds = $actionIds;
    }

    public function collection(): Collection
    {
        return Action::with([
            'process',
            'subProcess',
            'origin',
            'registeredBy',
            'responsibleBy',
            'status',
            'ending',
        ])
            ->whereIn('id', $this->actionIds)
            ->get();
    }

    public function map($action): array
    {
        return [
            $action->type?->label,
            $action->title,
            $action->description,
            $action->process?->title,
            $action->subProcess?->title,
            $action->origin?->title,
            $action->registration_date,
            $action->registeredBy?->name,
            $action->responsibleBy?->name,
            $action->status?->label,
            $action->expected_impact,
            $action->deadline,
            $action->actual_closing_date ?? __('Unclosed'),
            $action->ending?->real_impact ?? __('Unclosed'),
            $action->ending?->result ?? __('Unclosed'),
            $action->reason_for_cancellation ?? __('Empty'),
            $action->created_at?->format('Y-m-d H:i'),
            $action->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            __('Type'),
            __('Title'),
            __('Description'),
            __('Process'),
            __('Subprocess'),
            __('Origin'),
            __('Registration date'),
            __('Registered by'),
            __('Responsible'),
            __('Status'),
            __('Expected impact'),
            __('Deadline'),
            __('Actual closing date'),
            __('Real impact'),
            __('Result'),
            __('reason_for_cancellation'),
            __('Created at'),
            __('Updated at'),
        ];
    }
}
