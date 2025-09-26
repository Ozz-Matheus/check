<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class RiskCategoriesChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Risk Distribution by Category';

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListRisks::class;
    }

    protected function getData(): array
    {
        $data = $this->getPageTableQuery()
            ->reorder()
            ->join('risk_categories', 'risks.risk_category_id', '=', 'risk_categories.id')
            ->selectRaw('risk_categories.title, count(risks.id) as count')
            ->groupBy('risk_categories.title')
            ->pluck('count', 'title');

        return [
            'datasets' => [
                [
                    'label' => 'Risks by Category',
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

    /* public function getDescription(): ?string
    {
        return "This chart shows the proportion of risks belonging to each defined category. It provides an immediate overview of where the organization's risks are concentrated.";
    } */
}
