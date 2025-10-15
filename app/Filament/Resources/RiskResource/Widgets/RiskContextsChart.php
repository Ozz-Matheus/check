<?php

namespace App\Filament\Resources\RiskResource\Widgets;

use App\Models\Risk;
use App\Models\RiskStrategicContextType;
use Filament\Widgets\ChartWidget;

class RiskContextsChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'all';

    public function getHeading(): ?string
    {
        return __('Risk distribution by strategic context');
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => __('All'),
            'internal' => __('Internal'),
            'external' => __('External'),
        ];
    }

    protected function getData(): array
    {
        $query = Risk::query()
            ->join('risk_strategic_contexts', 'risks.strategic_context_id', '=', 'risk_strategic_contexts.id')
            ->selectRaw('risk_strategic_contexts.title, count(risks.id) as count')
            ->groupBy('risk_strategic_contexts.title');

        if ($this->filter !== 'all') {
            $contextTypeId = RiskStrategicContextType::where('name', $this->filter)->value('id');
            if ($contextTypeId) {
                $query->where('risk_strategic_contexts.strategic_context_type_id', $contextTypeId);
            }
        }

        $data = $query->pluck('count', 'title');

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
