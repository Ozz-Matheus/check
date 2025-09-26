<?php

namespace App\Services;

use App\Models\InternalAudit;
use App\Models\InternalAuditQualification;
use App\Models\SubProcess;
use Illuminate\Support\Facades\DB;

/**
 * Servicio para auditorias internas
 */
class InternalAuditService
{
    // Generar código de auditoría interna
    public function generateCode($subProcessId): string
    {
        return DB::transaction(function () use ($subProcessId) {

            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);

            $count = InternalAudit::where('sub_process_id', $subProcessId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "AUD-{$subProcess->acronym}-{$consecutive}";
        });
    }

    public function recalculateInternalAuditQualifications(InternalAudit $internalAudit)
    {
        // Obtener todos los elementos de auditoría que tienen un nivel general asignado
        $ratedAuditItems = $internalAudit->auditItems()
            ->whereNotNull('general_level_id')
            ->with('generalLevel')
            ->get();

        // Calcular el promedio de las puntuaciones de los niveles generales
        $averageScore = $ratedAuditItems->pluck('generalLevel.score')->average();

        // Encuentre la calificación de auditoría interna que se encuentre dentro del rango del puntaje promedio
        $qualification = InternalAuditQualification::where('min', '<=', $averageScore)
            ->where('max', '>=', $averageScore)
            ->first();

        // Actualice la auditoría interna con el nuevo valor de calificación e ID
        $internalAudit->update([
            'qualification_value' => $averageScore,
            'internal_audit_qualification_id' => $qualification?->id,
        ]);
    }
}
