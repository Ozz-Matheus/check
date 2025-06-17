<?php

namespace App\Exports;

use App\Models\Doc;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DocExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $DocIds;

    public function __construct(array $DocIds)
    {
        $this->DocIds = $DocIds;
    }

    public function collection(): Collection
    {
        return Doc::with([
            'type',
            'process',
            'subprocess',
            'createdBy',
            'latestVersion',
            'ending',
        ])
            ->whereIn('id', $this->DocIds)
            ->get();
    }

    public function map($doc): array
    {
        return [
            $doc->classification_code,
            $doc->title,
            $doc->type?->title,
            $doc->process?->title,
            $doc->subprocess?->title,
            $doc->latestVersion?->status?->label ?? __('Stateless'),
            $doc->latestVersion?->version ?? __('No version'),
            optional($doc->createdBy)->name,
            $doc->management_review_date?->format('Y-m-d'),
            $doc->central_expiration_date?->format('Y-m-d'),
            optional($doc->ending)->label,
            $doc->expiration ? 'Expired' : 'Current',
            $doc->created_at?->format('Y-m-d H:i'),
            $doc->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'Classification code',
            'Title',
            'Type',
            'Process',
            'Subprocess',
            'Status',
            'Version',
            'Created by',
            'Management time',
            'Central time',
            'Final disposition',
            'expiration',
            'Created at',
            'Updated at',
        ];
    }
}
