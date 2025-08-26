<?php

namespace App\Services;

use App\Models\Risk;
use App\Models\RiskControlQualification;
use App\Models\RiskImpact;
use App\Models\RiskLevel;
use App\Models\RiskProbability;

/**
 * Servicio para riesgos
 */
class RiskService
{
    public function riskLevel(int $riskImpact, int $riskProbability, ?int $controlQualification = null)
    {
        $riskLevelCalculated = (is_null($controlQualification))
            ? $this->riskInherentCalculated($riskImpact, $riskProbability)
            : $this->riskResidualCalculated($riskImpact, $riskProbability, $controlQualification);

        $riskLevel = RiskLevel::where('min', '<=', $riskLevelCalculated)->where('max', '>', $riskLevelCalculated)->first();

        if (! $riskLevel) {
            $riskLevel = RiskLevel::where('min', '<=', $riskLevelCalculated)
                ->where('max', '>=', $riskLevelCalculated)
                ->first();
        }

        return $riskLevel->id;
    }

    /* ********************************************************* */

    private function riskInherentCalculated(int $riskImpact, int $riskProbability)
    {
        $riskImpactScore = RiskImpact::findOrFail($riskImpact);
        $riskImpactScore = $riskImpactScore->score;

        $riskProbabilityScore = RiskProbability::findOrFail($riskProbability);
        $riskProbabilityScore = $riskProbabilityScore->score;

        $riskInherentValueCalculated = $riskImpactScore * $riskProbabilityScore;

        return $riskInherentValueCalculated;
    }

    private function riskResidualCalculated(int $riskImpact, int $riskProbability, int $controlQualification)
    {
        /* $riskControlQualificationScore = RiskControlQualification::findOrFail($controlQualification);
        $riskControlQualificationScore = $riskControlQualificationScore->score; */ // Se hace con el valor predeterminado de la calificación del control con mas cercanía
        $riskControlQualificationScore = $controlQualification; // Se hace con el calculo exacto

        $riskInherentCalculated = $this->riskInherentCalculated($riskImpact, $riskProbability);

        $riskResidualValueCalculated = ceil((1 - ($riskControlQualificationScore * 0.01)) * $riskInherentCalculated);

        return $riskResidualValueCalculated;
    }

    /* ********************************************************* */

    public function recalculateRiskControlQualifications(Risk $risk): void
    {
        // Obtener todos los IDs de calificaciones de controles del riesgo
        $qualificationIds = $risk->controls()
            ->pluck('control_qualification_id')
            ->filter(); // Elimina nulos

        if ($qualificationIds->isEmpty()) {
            $risk->update([
                'risk_control_general_qualification_id' => null,
                'residual_risk_level_id' => null,
            ]);

            return;
        }

        // Calcular promedio de scores
        $average = $this->averageControlQualification($qualificationIds);

        // Buscar la calificación más cercana al promedio
        $closest = $this->valueClosestAverage($average);

        // Calcular nivel de riesgo residual
        $residualLevelId = $this->riskLevel(
            $risk->inherent_impact_id,
            $risk->inherent_probability_id,
            $average
        );

        // Actualizar campos en el riesgo
        $risk->update([
            'risk_control_general_qualification_id' => $closest?->id,
            'residual_risk_level_id' => $residualLevelId,
        ]);
    }

    private function averageControlQualification($qualificationIds)
    {
        // Obtenemos los scores únicos desde la base de datos (una sola vez por ID)
        $scoresMap = RiskControlQualification::whereIn('id', $qualificationIds->unique())
            ->pluck('score', 'id'); // [id => score]

        // Mapeamos los scores respetando las repeticiones de IDs
        $scores = $qualificationIds->map(fn ($id) => $scoresMap[$id] ?? 0);

        // Se calcula el promedio el promedio
        $average = $scores->average();

        return round($average);
    }

    private function valueClosestAverage($average)
    {
        // Obtenemos todas las clasificaciones con su puntaje
        $allQualifications = RiskControlQualification::all(['id', 'score']);

        // Buscamos la clasificación cuyo score esté más cerca al promedio
        $closest = $allQualifications->sortBy(function ($item) use ($average) {
            return abs($item->score - $average);
        })->first();

        return $closest;
    }
}
