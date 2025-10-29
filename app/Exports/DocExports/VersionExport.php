<?php

namespace App\Exports\DocExports;

use App\Models\DocVersion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class VersionExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected array $versionIds;

    public function __construct(array $versionIds)
    {
        $this->versionIds = $versionIds;
    }

    public function title(): string
    {
        return '📚 Versiones seleccionadas';
    }

    public function collection(): Collection
    {
        return DocVersion::with(['status', 'doc'])
            ->whereIn('id', $this->versionIds)
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
