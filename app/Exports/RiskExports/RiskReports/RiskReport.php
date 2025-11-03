<?php

namespace App\Exports\RiskExports\RiskReports;

use App\Models\Process;
use App\Models\Risk;
use App\Models\RiskLevel;
use App\Models\SubProcess;

class RiskReport
{
    public static function make(array $data): array
    {
        $process = Process::find($data['process_id']);
        $subprocess = SubProcess::find($data['sub_process_id']);

        $risks = Risk::with([
            'controls',
            'process',
            'subProcess',
            'residualLevel',
        ])
            ->where('process_id', $data['process_id'])
            ->where('sub_process_id', $data['sub_process_id'])
            ->get();

        // Obtiene todos los niveles de riesgo posibles para garantizar que todos estén presentes en la matriz final, incluso con un recuento de 0.
        $allRiskLevels = RiskLevel::pluck('title')
            ->mapWithKeys(fn ($title) => [strtolower($title) => 0]);

        // Cuenta los riesgos por su nivel residual.
        $riskLevelCounts = $risks->countBy(fn ($risk) => strtolower($risk->residualLevel?->title));

        // Fusiona los recuentos en la matriz de todos los niveles.
        $totals = $allRiskLevels->merge($riskLevelCounts)->all();

        return [
            'process' => $process,
            'subprocess' => $subprocess,
            'risks' => $risks,
            'totals' => $totals,
        ];
    }
}
