<?php

namespace App\Services;

use App\Models\AuditImpact;
use App\Models\AuditItem;
use App\Models\AuditLevel;
use App\Models\AuditProbability;

class AuditControlService
{
    public function calculatedLevel(int $impact, int $probability)
    {
        $impactScore = AuditImpact::findOrFail($impact);
        $impactScore = $impactScore->score;

        $probabilityScore = AuditProbability::findOrFail($probability);
        $probabilityScore = $probabilityScore->score;

        $levelCalculated = $impactScore * $probabilityScore;

        $riskLevel = AuditLevel::where('min', '<=', $levelCalculated)->where('max', '>', $levelCalculated)->first();

        if (! $riskLevel) {
            $riskLevel = AuditLevel::where('min', '<=', $levelCalculated)
                ->where('max', '>=', $levelCalculated)
                ->first();
        }

        return $riskLevel->id;
    }

    public function calculatedAuditItemLevel(AuditItem $auditItem)
    {
        // Obtenga todos los controles calificados que tengan un nivel asignado
        $qualifiedControls = $auditItem->controls()
            ->where('qualified', true)
            ->whereNotNull('level_id')
            ->with('level')
            ->get();

        if ($qualifiedControls->isEmpty()) {
            $auditItem->update(['general_level_id' => null]);

            return;
        }

        // Obtenga la puntuación de cada nivel de control y calcule el promedio
        $averageScore = $qualifiedControls->map(function ($control) {
            return $control->level->score ?? 0;
        })->average();

        // Encuentre el nivel de auditoría con la puntuación más cercana al promedio
        $allLevels = AuditLevel::all();
        $closestLevel = $allLevels->sortBy(function ($level) use ($averageScore) {
            return abs($level->score - $averageScore);
        })->first();

        // dd($averageScore, $closestLevel);
        $auditItem->update(['general_level_id' => $closestLevel?->id]);
    }
}
