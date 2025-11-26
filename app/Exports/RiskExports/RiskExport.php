<?php

namespace App\Exports\RiskExports;

use App\Models\Risk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class RiskExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected array $riskIds;

    public function __construct(array $riskIds)
    {
        $this->riskIds = $riskIds;
    }

    public function title(): string
    {
        return '⚠️ Riesgos seleccionados';
    }

    public function collection(): Collection
    {
        return Risk::with([
            'process',
            'subProcess',
            'riskCategory',
            'strategicContextType',
            'strategicContext',
            'inherentImpact',
            'inherentProbability',
            'inherentLevel',
            'residualImpact',
            'residualProbability',
            'residualLevel',
            'controlGeneralQualificationCalculated',
        ])
            ->whereIn('id', $this->riskIds)
            ->get();
    }

    public function map($risk): array
    {
        return [
            $risk->classification_code,
            $risk->title,
            $risk->process?->title,
            $risk->subProcess?->title,
            $risk->riskCategory?->title,
            $risk->strategicContextType?->label,
            $risk->strategicContext?->title,
            $risk->description,
            $risk->inherentImpact?->title,
            $risk->inherentProbability?->title,
            $risk->inherentLevel?->title,
            $risk->residualImpact?->title,
            $risk->residualProbability?->title,
            $risk->residualLevel?->title,
            $risk->controlGeneralQualificationCalculated?->title,
            $risk->created_at?->format('Y-m-d H:i'),
            $risk->updated_at?->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            __('Classification code'),
            __('Title'),
            __('Process'),
            __('Sub process'),
            __('Risk category'),
            __('Strategic context type'),
            __('Strategic context'),
            __('Description'),
            __('Inherent impact'),
            __('Inherent probability'),
            __('Inherent level'),
            __('Residual impact'),
            __('Residual probability'),
            __('Residual level'),
            __('Control general qualification'),
            __('Created at'),
            __('Updated at'),
        ];
    }
}
