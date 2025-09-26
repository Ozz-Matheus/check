<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Filament\Resources\DocResource\Pages\ListDocs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Facades\DB;

class DocTypesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Documents by Type';

    protected static ?string $maxHeight = '300px';

    protected function getTablePage(): string
    {
        return ListDocs::class;
    }

    protected function getData(): array
    {
        $data = $this->getPageTableQuery()
            ->reorder() // Remove any existing ordering from the table query
            ->join('doc_types', 'docs.doc_type_id', '=', 'doc_types.id')
            ->select('doc_types.name as type_name', DB::raw('count(*) as count'))
            ->groupBy('type_name')
            ->orderBy('type_name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Document types',
                    'data' => $data->pluck('count')->toArray(),
                ],
            ],
            'labels' => $data->pluck('type_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
