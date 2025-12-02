<?php

namespace App\Traits;

use App\Models\DocVersion;
use App\Models\SubProcess;

trait HasUserLogic
{
    // Metodos para el usuario Leader

    public function getLeaderToSubProcess(?int $subProcessId)
    {
        $subProcess = SubProcess::find($subProcessId);

        // Retorna el primer (y único) líder del subproceso
        return $subProcess?->leaders()->first();
    }

    public function isLeaderOfSubProcess(?int $subProcessId): bool
    {
        return $this->leaderOf()->where('sub_process_id', $subProcessId)->exists();
    }

    public function validSubProcess($subProcessId): bool
    {
        return $this->subProcesses()->where('sub_process_id', $subProcessId)->exists();
    }

    public function canAccessSubProcess(int|string|null $subProcessId): bool
    {
        return $this->hasRole('super_admin') ||
            ($subProcessId !== null && $this->validSubProcess($subProcessId));
    }

    // Verificación de permisos para el cambiar de estado de una versión del documento.

    public function canPending(DocVersion $docVersion): bool
    {
        return $this->hasRole('super_admin') || $docVersion->created_by_id === $this->id;
    }

    public function canApproveAndReject(?int $subProcessId): bool
    {
        return $this->hasRole('super_admin') || $this->isLeaderOfSubProcess($subProcessId);
    }
}
