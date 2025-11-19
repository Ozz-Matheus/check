<?php

namespace App\Exports\RiskExports\RiskReports;

use App\Models\Process;
use App\Models\Risk;
use App\Models\RiskImpact;
use App\Models\RiskLevel;
use App\Models\RiskProbability;
use App\Models\SubProcess;

class RiskReport
{
    public static function make(array $data): array
    {
        $process = Process::find($data['process_id']);
        $subprocess = SubProcess::with('leader')->find($data['sub_process_id']);

        // Cargar todos los riesgos con sus relaciones
        $risks = Risk::with([
            'controls.periodicity',
            'controls.controlType',
            'controls.controlQualification',
            'controls.followUps',
            'process',
            'subProcess',
            'riskCategory',
            'strategicContextType',
            'strategicContext',
            'potentialCauses.controls.periodicity',
            'potentialCauses.controls.controlType',
            'potentialCauses.controls.controlQualification',
            'inherentImpact',
            'inherentProbability',
            'inherentLevel',
            'residualImpact',
            'residualProbability',
            'residualLevel',
            'controlGeneralQualificationCalculated',
            'actions.responsibleBy',
        ])
            ->where('process_id', $data['process_id'])
            ->where('sub_process_id', $data['sub_process_id'])
            ->orderBy('residual_risk_level_id', 'desc')
            ->get();

        // Obtener todos los niveles de riesgo ordenados
        $allRiskLevels = RiskLevel::orderBy('id')->get();

        // Preparar estructura para totales inherentes
        $inherentTotals = $allRiskLevels->mapWithKeys(fn ($level) => [
            strtolower($level->title) => 0,
        ]);

        // Preparar estructura para totales residuales
        $residualTotals = $allRiskLevels->mapWithKeys(fn ($level) => [
            strtolower($level->title) => 0,
        ]);

        // Contar riesgos por nivel inherente
        $inherentCounts = $risks->countBy(fn ($risk) => strtolower($risk->inherentLevel?->title)
        );

        // Contar riesgos por nivel residual
        $residualCounts = $risks->countBy(fn ($risk) => strtolower($risk->residualLevel?->title)
        );

        // Fusionar conteos
        $inherentTotals = $inherentTotals->merge($inherentCounts)->all();
        $residualTotals = $residualTotals->merge($residualCounts)->all();

        // Calcular reducción de riesgo basada en puntuación (Impacto × Probabilidad)
        $totalInherentScore = 0;
        $totalResidualScore = 0;

        foreach ($risks as $risk) {
            if ($risk->inherentImpact && $risk->inherentProbability) {
                $totalInherentScore += ($risk->inherentImpact->weight * $risk->inherentProbability->weight);
            }
            if ($risk->residualImpact && $risk->residualProbability) {
                $totalResidualScore += ($risk->residualImpact->weight * $risk->residualProbability->weight);
            }
        }

        $riskReduction = 0;
        $riskReductionPercentage = 0;
        if ($totalInherentScore > 0) {
            $riskReduction = $totalInherentScore - $totalResidualScore;
            $riskReductionPercentage = ($riskReduction / $totalInherentScore) * 100;
        }

        // Contar cuántos riesgos mejoraron de nivel
        $risksImprovedCount = 0;
        $risksWorsenedCount = 0;
        $risksUnchangedCount = 0;

        foreach ($risks as $risk) {
            $inherentLevelId = $risk->inherent_risk_level_id;
            $residualLevelId = $risk->residual_risk_level_id;

            if ($inherentLevelId > $residualLevelId) {
                $risksImprovedCount++;
            } elseif ($inherentLevelId < $residualLevelId) {
                $risksWorsenedCount++;
            } else {
                $risksUnchangedCount++;
            }
        }

        // Obtener impacts y probabilities ordenados
        $impacts = RiskImpact::orderBy('weight', 'desc')->get();
        $probabilities = RiskProbability::orderBy('weight')->get();

        // Generar matriz de calor inherente
        $inherentHeatmapData = self::generateHeatmapData(
            $risks,
            $impacts,
            $probabilities,
            'inherent'
        );

        // Generar matriz de calor residual
        $residualHeatmapData = self::generateHeatmapData(
            $risks,
            $impacts,
            $probabilities,
            'residual'
        );

        // Calcular niveles para cada celda de la matriz
        $matrixLevels = self::calculateMatrixLevels($impacts, $probabilities);

        // Estadísticas adicionales
        $statistics = [
            'total_risks' => $risks->count(),
            'total_controls' => $risks->sum(fn ($risk) => $risk->controls->count()),
            'total_causes' => $risks->sum(fn ($risk) => $risk->potentialCauses->count()),
            'total_actions' => $risks->sum(fn ($risk) => $risk->actions->count()),
            'risks_with_actions' => $risks->filter(fn ($risk) => $risk->actions->count() > 0)->count(),
            'avg_controls_per_risk' => $risks->count() > 0
                ? round($risks->sum(fn ($risk) => $risk->controls->count()) / $risks->count(), 1)
                : 0,
            'total_inherent_score' => $totalInherentScore,
            'total_residual_score' => $totalResidualScore,
            'risk_reduction' => $riskReduction,
            'risk_reduction_percentage' => round($riskReductionPercentage, 2),
            'risks_improved' => $risksImprovedCount,
            'risks_worsened' => $risksWorsenedCount,
            'risks_unchanged' => $risksUnchangedCount,
        ];

        // Agrupar riesgos por categoría
        $risksByCategory = $risks->groupBy(fn ($risk) => $risk->riskCategory?->title ?? 'Sin categoría'
        )->map(fn ($group) => $group->count());

        // Agrupar riesgos por contexto estratégico
        $risksByContext = $risks->groupBy(fn ($risk) => $risk->strategicContext?->title ?? 'Sin contexto'
        )->map(fn ($group) => $group->count());

        return [
            'process' => $process,
            'subprocess' => $subprocess,
            'risks' => $risks,
            'inherentTotals' => $inherentTotals,
            'residualTotals' => $residualTotals,
            'allRiskLevels' => $allRiskLevels,
            'impacts' => $impacts,
            'probabilities' => $probabilities,
            'inherentHeatmapData' => $inherentHeatmapData,
            'residualHeatmapData' => $residualHeatmapData,
            'matrixLevels' => $matrixLevels,
            'statistics' => $statistics,
            'risksByCategory' => $risksByCategory,
            'risksByContext' => $risksByContext,
        ];
    }

    /**
     * Genera datos para el mapa de calor
     */
    private static function generateHeatmapData($risks, $impacts, $probabilities, $type = 'residual')
    {
        $heatmapData = [];

        foreach ($impacts as $impact) {
            foreach ($probabilities as $probability) {
                $heatmapData[$impact->id][$probability->id] = 0;
            }
        }

        foreach ($risks as $risk) {
            if ($type === 'inherent') {
                $impactId = $risk->inherent_impact_id;
                $probabilityId = $risk->inherent_probability_id;
            } else {
                $impactId = $risk->residual_impact_id;
                $probabilityId = $risk->residual_probability_id;
            }

            if ($impactId && $probabilityId) {
                if (! isset($heatmapData[$impactId][$probabilityId])) {
                    $heatmapData[$impactId][$probabilityId] = 0;
                }
                $heatmapData[$impactId][$probabilityId]++;
            }
        }

        return $heatmapData;
    }

    /**
     * Calcula el nivel de riesgo para cada celda de la matriz
     */
    private static function calculateMatrixLevels($impacts, $probabilities)
    {
        $matrixLevels = [];
        $riskLevels = RiskLevel::orderBy('min')->get();

        foreach ($impacts as $impact) {
            foreach ($probabilities as $probability) {
                $score = $impact->weight * $probability->weight;

                $level = $riskLevels->first(function ($riskLevel) use ($score) {
                    return $score >= $riskLevel->min && $score <= $riskLevel->max;
                });

                $matrixLevels[$impact->id][$probability->id] = $level;
            }
        }

        return $matrixLevels;
    }
}
