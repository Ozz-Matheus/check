<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class ResidualRisksChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'amount';

    public function getHeading(): ?string
    {
        return __('Risk distribution by residual level');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('It is referenced to the list filters');
    }

    protected function getFilters(): ?array
    {
        return [
            'amount' => __('Amount'),
            'percentage' => __('Percentage'),
        ];
    }

    protected function getTablePage(): string
    {
        return ListRisks::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $data = $query->reorder()
            ->join('risk_levels', 'risks.residual_risk_level_id', '=', 'risk_levels.id')
            ->selectRaw('risk_levels.title, risk_levels.color, count(risks.id) as count')
            ->groupBy('risk_levels.title', 'risk_levels.color')
            ->get();

        if ($this->filter === 'percentage') {
            $total = $data->sum('count');

            if ($total > 0) {
                $data->each(function ($item) use ($total) {
                    $item->count = round(($item->count / $total) * 100, 2);
                });
            }
        }

        $colors = $data->pluck('color')->map(function ($colorName) {
            return config("filament-colors.{$colorName}.rgba", 'rgba(156, 163, 175, 1)');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('Risks by residual level'),
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $data->pluck('title')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
