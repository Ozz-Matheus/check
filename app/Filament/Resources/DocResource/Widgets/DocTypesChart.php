<?php

namespace App\Filament\Resources\DocResource\Widgets;

use App\Filament\Resources\DocResource\Pages\ListDocs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class DocTypesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Documents by type');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('It is referenced to the list filters');
    }

    protected function getTablePage(): string
    {
        return ListDocs::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $data = $query->reorder() // Eliminar cualquier orden existente para el rendimiento
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
