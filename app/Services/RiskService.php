<?php

namespace App\Services;

use App\Models\Risk;
use App\Models\RiskControlQualification;
use App\Models\RiskImpact;
use App\Models\RiskLevel;
use App\Models\RiskProbability;
use App\Models\SubProcess;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Servicio para riesgos
 */
class RiskService
{
    public function inherentLevel(int $inherentImpactId, int $inherentProbabilityId)
    {
        // 1. Buscamos los pesos para el impacto y la probabilidad inherentes.
        $inherentImpactWeight = RiskImpact::findOrFail($inherentImpactId)->weight;
        $inherentProbabilityWeight = RiskProbability::findOrFail($inherentProbabilityId)->weight;

        // 2. Calculamos el nivel de riesgo inherente.
        $inherentLevelCalculation = $inherentImpactWeight * $inherentProbabilityWeight;

        // 3. Asignamos el nivel de riesgo inherente.
        $inherentRiskLevel = RiskLevel::where('min', '<=', $inherentLevelCalculation)
            ->where('max', '>', $inherentLevelCalculation)
            ->first();

        if (! $inherentRiskLevel) {
            $inherentRiskLevel = RiskLevel::where('min', '<=', $inherentLevelCalculation)
                ->where('max', '>=', $inherentLevelCalculation)
                ->first();
        }

        // 4. Devolvemos el ID del nivel de riesgo inherente.
        return $inherentRiskLevel->id;
    }

    public function getControlsByContext(Risk $risk): Collection
    {
        // 1. Obtenga todos los controles de carga anticipada y sus calificaciones para prevenir problemas de consultas N+1.
        $controls = $risk->controls()->with('controlQualification')->get();

        // 2. Los controles de grupo se rigen por su contexto ('prevención' o 'materialización').
        $groupedByContext = $controls->groupBy(function ($control) {
            return $control?->context_type;
        });

        // 3. Calcule el factor de reducción promedio para cada contexto.
        $avgReductionPrevention = $groupedByContext->get('prevention', collect())->avg(function ($control) {
            return $control->controlQualification->reduction_factor ?? 0;
        }) ?? 0;

        $avgReductionMaterialization = $groupedByContext->get('materialization', collect())->avg(function ($control) {
            return $control->controlQualification->reduction_factor ?? 0;
        }) ?? 0;

        return collect(['prevention' => $avgReductionPrevention, 'materialization' => $avgReductionMaterialization]);
    }

    public function calculateResidualDimensions(Risk $risk): Collection
    {
        // 1. Obtenga los factores de reducción promedio y conviértalos a decimales (por ejemplo, 40 a 0,4).
        $averageReductions = $this->getControlsByContext($risk);
        $preventionFactor = $averageReductions->get('prevention', 0) / 100;
        $materializationFactor = $averageReductions->get('materialization', 0) / 100;

        // 2. Calcular la probabilidad residual.
        $inherentProbabilityWeight = $risk->inherentProbability->weight;
        $residualProbabilityCalculation = $inherentProbabilityWeight * (1 - $preventionFactor);

        // 3. Encuentre la probabilidad de riesgo más cercana por ponderación.
        $closestProbability = RiskProbability::all()->sortBy(function ($probability) use ($residualProbabilityCalculation) {
            return abs($probability->weight - $residualProbabilityCalculation);
        })->first();

        $residualProbabilityId = $closestProbability?->id ?? $risk->inherent_probability_id;

        // 4. Calcular el impacto residual.
        $inherentImpactWeight = $risk->inherentImpact->weight;
        $residualImpactCalculation = $inherentImpactWeight * (1 - $materializationFactor);

        // 5. Encuentre el impacto de riesgo más cercano por ponderación.
        $closestImpact = RiskImpact::all()->sortBy(function ($impact) use ($residualImpactCalculation) {
            return abs($impact->weight - $residualImpactCalculation);
        })->first();

        $residualImpactId = $closestImpact?->id ?? $risk->inherent_impact_id;

        // 6. Obtenga los pesos de las nuevas dimensiones residuales.
        $residualProbabilityWeight = $closestProbability?->weight ?? $risk->inherentProbability->weight;
        $residualImpactWeight = $closestImpact?->weight ?? $risk->inherentImpact->weight;

        // 7. Calcular el valor del nivel de riesgo residual.
        $residualRiskLevelCalculation = $residualProbabilityWeight * $residualImpactWeight;

        // 8. Encuentre el nivel de riesgo correspondiente según el valor calculado.
        $residualRiskLevel = RiskLevel::where('min', '<=', $residualRiskLevelCalculation)
            ->where('max', '>', $residualRiskLevelCalculation)
            ->first();

        if (! $residualRiskLevel) {
            $residualRiskLevel = RiskLevel::where('min', '<=', $residualRiskLevelCalculation)
                ->where('max', '>=', $residualRiskLevelCalculation)
                ->first();
        }

        // Devuelve los ID calculados.
        return collect([
            'residual_probability_id' => $residualProbabilityId,
            'residual_impact_id' => $residualImpactId,
            'residual_risk_level_id' => $residualRiskLevel?->id,
        ]);
    }

    private function calculateOverallControlQualification(Risk $risk): ?RiskControlQualification
    {
        // 1. Obtener todos los ID de calificación de los controles del riesgo, filtrando los valores nulos.
        $qualificationIds = $risk->controls()->pluck('control_qualification_id')->filter();

        $averageQualificationValue = 0;
        if ($qualificationIds->isNotEmpty()) {
            // 2. Obtenga el factor de reducción para cada ID de calificación único.
            $reductionFactorMap = RiskControlQualification::whereIn('id', $qualificationIds->unique())
                ->pluck('reduction_factor', 'id');

            // 3. Asigne el factor de reducción a todos los controles, respetando los duplicados.
            $reductionFactors = $qualificationIds->map(fn ($id) => $reductionFactorMap[$id] ?? 0);

            // 4. Calcula el factor de reducción promedio y redondéalo.
            $averageQualificationValue = round($reductionFactors->average());
        }

        // 5. Encuentra la calificación con un factor de reducción más cercano al promedio calculado.
        $allQualifications = RiskControlQualification::all();

        return $allQualifications->sortBy(function ($item) use ($averageQualificationValue) {
            return abs($item->reduction_factor - $averageQualificationValue);
        })->first();
    }

    public function updateResidualRisk(Risk $risk): bool
    {
        // 1. Calcular todos los nuevos identificadores de dimensión residual.
        $residualDimensions = $this->calculateResidualDimensions($risk);

        // 2. Obtenga la calificación de control general.
        $closestQualification = $this->calculateOverallControlQualification($risk);

        // 3. Preparar datos para actualizar el riesgo.
        $updateData = [
            'residual_probability_id' => $residualDimensions->get('residual_probability_id'),
            'residual_impact_id' => $residualDimensions->get('residual_impact_id'),
            'residual_risk_level_id' => $residualDimensions->get('residual_risk_level_id'),
            'risk_control_general_qualification_id' => $closestQualification?->id,
        ];

        // 4. Actualizar el modelo de riesgo.
        return $risk->update($updateData);
    }

    /* ********************************************************* */

    // Generar código de riesgo
    public function generateCode($subProcessId): string
    {
        return DB::transaction(function () use ($subProcessId) {

            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);

            $count = Risk::where('sub_process_id', $subProcessId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "R-{$subProcess->acronym}-{$consecutive}";
        });
    }
}
