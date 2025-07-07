<?php

namespace App\Traits;

use App\Models\Audit;
use App\Models\DocVersion;
use App\Models\SubProcess;

trait HasUserLogic
{
    // Metodos para el usuario Leader

    public function leaderOfSubProcess(): ?SubProcess
    {
        return SubProcess::with('process')
            ->where('leader_by_id', $this->id)
            ->first();
    }

    public function getLeaderToSubProcess(?int $subProcessId)
    {
        return SubProcess::with('leader')->find($subProcessId)?->leader;
    }

    public function isLeaderOfSubProcess(?int $subProcessId): bool
    {
        return SubProcess::where('id', $subProcessId)
            ->where('leader_by_id', $this->id)
            ->exists();
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

    // Metodos para auditoria

    /* public function canCreateFinding(Audit $audit)
    {
        return auth()->id() === $audit->leader_auditor_id;
    } */
}
