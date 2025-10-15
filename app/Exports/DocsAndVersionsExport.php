<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DocsAndVersionsExport implements WithMultipleSheets
{
    protected array $docIds;

    public function __construct(array $docIds)
    {
        $this->docIds = $docIds;
    }

    public function sheets(): array
    {
        return [
            'Documentos' => new DocExport($this->docIds),
            'Versiones' => new DocExportWithVersion($this->docIds),
        ];
    }
}
