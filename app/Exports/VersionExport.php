<?php

namespace App\Exports;

use App\Models\DocVersion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VersionExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $versionIds;

    public function __construct(array $versionIds)
    {
        $this->versionIds = $versionIds;
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
            $version->file->name,
            $version->version,
            optional($version->status)->label,
            $version->sha256_hash ? 'Yes' : 'No',
            optional($version->doc)->classification_code,
            $version->isLatestVersion() ? 'Yes' : 'No',
            $version->isCompliant() ? 'Yes' : 'No',
            $version->comment ?? '—',
            $version->change_reason ?? '—',
            optional($version->createdBy)->name,
            $version->created_at,
            $version->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'File',
            'Version',
            'Status',
            'Signed',
            'Classification',
            'Latest Version',
            'Meets Requirements',
            'Comment',
            'Reason for change',
            'Created By',
            'Created',
            'Updated',
        ];
    }
}
