<?php

namespace App\Exports;

use App\Models\Doc;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DocExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected array $DocIds;

    public function __construct(array $DocIds)
    {
        $this->DocIds = $DocIds;
    }

    public function title(): string
    {
        return '📄 Documentos seleccionados';
    }

    public function collection(): Collection
    {
        return Doc::with([
            'type',
            'process',
            'subprocess',
            'createdBy',
            'latestVersion',
        ])
            ->whereIn('id', $this->DocIds)
            ->get();
    }

    public function map($doc): array
    {
        return [
            $doc->classification_code,
            $doc->title,
            $doc->type?->label,
            $doc->process?->title,
            $doc->subprocess?->title,
            $doc->latestVersion?->status?->label ?? __('Stateless'),
            $doc->latestVersion?->version ?? __('No version'),
            $doc->central_expiration_date?->format('d-m-Y'),
            $doc->is_expired ? __('Expired') : __('Current'),
            $doc->display_restriction ? __('Private') : __('Public'),
            $doc->storageMethod?->label ?? '-',
            $doc->recoveryMethod?->title ?? '-',
            $doc->dispositionMethod?->title ?? '-',
            optional($doc->createdBy)->name,
            optional($doc->createdBy)->email,
            $doc->created_at,
            $doc->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            __('Classification code'),
            __('Title'),
            __('Doc type'),
            __('Process'),
            __('Sub process'),
            __('Status'),
            __('Version'),
            __('Central expiration date'),
            __('Expiration status'),
            __('Display restriction'),
            __('Storage method'),
            __('Recovery method'),
            __('Disposition method'),
            __('Created by (name)'),
            __('Created by (email)'),
            __('Created at'),
            __('Updated at'),
        ];
    }
}
