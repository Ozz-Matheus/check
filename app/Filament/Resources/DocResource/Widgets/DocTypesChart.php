<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Models\Doc;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DocTypesChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Documents by type');
    }

    protected function getData(): array
    {
        $data = Doc::reorder() // Remove any existing ordering from the table query
            ->join('doc_types', 'docs.doc_type_id', '=', 'doc_types.id')
            ->select('doc_types.label as type_label', DB::raw('count(*) as count'))
            ->groupBy('type_label')
            ->orderBy('type_label')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Document types'),
                    'data' => $data->pluck('count')->toArray(),
                ],
            ],
            'labels' => $data->pluck('type_label')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
