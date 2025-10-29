<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Filament\Resources\RiskResource\Pages\ListRisks;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Contracts\Support\Htmlable;

class RiskContextsChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('Risk distribution by strategic context');
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
            ->join('risk_strategic_contexts', 'risks.strategic_context_id', '=', 'risk_strategic_contexts.id')
            ->selectRaw('risk_strategic_contexts.title, count(risks.id) as count')
            ->groupBy('risk_strategic_contexts.title')
            ->pluck('count', 'title');

        return [
            'datasets' => [
                [
                    'label' => __('Risks by context'),
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
