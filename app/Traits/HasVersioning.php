<?php

namespace App\Traits;

trait HasVersioning
{
    /**
     * Calcula la nueva versión basándose en la anterior y los permisos/estado.
     */
    private function calculateNewVersion(?string $lastVersion, bool $hasApprovalAccess, ?string $status = null): string
    {
        // 1️⃣ Si no existe, retornamos el inicial y salimos.
        if (blank($lastVersion)) {
            return '0.01';
        }

        // 2️⃣ Cálculo de la versión
        return ($hasApprovalAccess && $status === 'approved')
            ? ((int) $lastVersion + 1).'.00'
            : bcadd($lastVersion, '0.01', 2);
    }
}
