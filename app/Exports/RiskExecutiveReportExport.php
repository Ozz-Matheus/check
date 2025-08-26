<?php

namespace App\Exports;

use App\Models\Risk;
use App\Models\Process;
use App\Models\SubProcess;

class RiskExecutiveReportExport
{
    public static function make(array $data): array
    {
        $process = Process::find($data['process_id']);
        $subprocess = SubProcess::find($data['sub_process_id']);

        $risks = Risk::with([
                'controls',
                'process',
                'subProcess',
                'residualLevelCalculated',
            ])
            ->where('process_id', $data['process_id'])
            ->where('sub_process_id', $data['sub_process_id'])
            ->get();

        $totals = [
            'crítico' => 0,
            'alto' => 0,
            'moderado' => 0,
            'bajo' => 0,
        ];

        foreach ($risks as $risk) {
            $level = strtolower($risk->residualLevelCalculated?->title);

            if (array_key_exists($level, $totals)) {
                $totals[$level]++;
            }
        }

        return [
            'process' => $process,
            'subprocess' => $subprocess,
            'risks' => $risks,
            'totals' => $totals,
        ];
    }
}
