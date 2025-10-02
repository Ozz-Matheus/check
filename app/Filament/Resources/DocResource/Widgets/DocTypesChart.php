<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Models\Doc;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DocTypesChart extends ChartWidget
{
    protected static ?string $heading = 'Documents by Type';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Doc::reorder() // Remove any existing ordering from the table query
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
