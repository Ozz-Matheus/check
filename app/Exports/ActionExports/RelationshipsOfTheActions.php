<?php

namespace App\Exports\ActionExports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RelationshipsOfTheActions implements WithMultipleSheets
{
    protected array $actionIds;

    public function __construct(array $actionIds)
    {
        $this->actionIds = $actionIds;
    }

    public function sheets(): array
    {
        return [
            'Acciones' => new ActionExport($this->actionIds),
            'Tareas' => new TasksRelatedToAction($this->actionIds),
        ];
    }
}
