<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Models\Risk;
use Filament\Widgets\ChartWidget;

class RiskCategoriesChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Risk distribution by category');
    }

    protected function getData(): array
    {
        $data = Risk::reorder()
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
