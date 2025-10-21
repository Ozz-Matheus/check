<?php

namespace App\Exports;

use App\Models\DocVersion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DocExportWithVersion implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected array $docIds;

    public function __construct(array $docIds)
    {
        $this->docIds = $docIds;
    }

    public function title(): string
    {
        return '📚 Versiones relacionadas';
    }

    public function collection(): Collection
    {
        return DocVersion::query()
            ->whereIn('doc_id', $this->docIds)
            ->get();
    }

    public function map($version): array
    {
        return [
            $version->doc->classification_code,
            $version->file->name,
            $version->file->readable_mime_type,
            $version->file->readable_size,
            $version->status->label ?? __('Stateless'),
            $version->version ?? __('No version'),
            $version->comment ?? '—',
            $version->change_reason ?? '—',
            optional($version->createdBy)->name,
            optional($version->createdBy)->email,
            optional($version->decidedBy)->name,
            optional($version->decidedBy)->email,
            $version->decision_at ?? '—',
            $version->sha256_hash ? __('Yes') : __('No'),
            $version->isLatestVersion() ? __('Yes') : __('No'),
            $version->isCompliant() ? 'Yes' : 'No',
            $version->created_at,
            $version->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            __('Classification code'),
            __('Name'),
            __('Type'),
            __('Size'),
            __('Status'),
            __('Version'),
            __('Comment'),
            __('Reason for change'),
            __('Created by (name)'),
            __('Created by (email)'),
            __('Decided by (name)'),
            __('Decided by (email)'),
            __('Decision at'),
            __('Signed'),
            __('Latest Version'),
            __('Meets Requirements'),
            __('Created at'),
            __('Updated at'),
        ];
    }
}
