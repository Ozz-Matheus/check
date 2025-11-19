<?php

namespace App\Exports\DocExports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RelationshipsOfTheDocs implements WithMultipleSheets
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
            'Versiones' => new VersionsRelatedToDoc($this->docIds),
        ];
    }
}
