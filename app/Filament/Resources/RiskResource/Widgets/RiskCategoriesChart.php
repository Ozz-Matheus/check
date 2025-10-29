<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class RiskCategoriesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Risk distribution by category');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('It is referenced to the list filters');
    }

    protected function getTablePage(): string
    {
        return ListRisks::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $data = $query->reorder()
            ->join('risk_categories', 'risks.risk_category_id', '=', 'risk_categories.id')
            ->selectRaw('risk_categories.title, count(risks.id) as count')
            ->groupBy('risk_categories.title')
            ->pluck('count', 'title');

        return [
            'datasets' => [
                [
                    'label' => __('Risks by category'),
                    'data' => $data->values()->toArray(),
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
