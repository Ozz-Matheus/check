<?php

namespace App\Services;

use App\Models\Action;
use App\Models\IAndAEventType;
use App\Models\IncidentAndAccident;
use App\Models\SubProcess;
use Illuminate\Support\Facades\DB;

class IncidentAndAccidentService
{
    protected array $statusIds;

    public function __construct(StatusService $statusService)
    {
        $this->statusIds = $statusService->getIncidentAndAccidentStatuses();
    }

    public function generateCode($eventTypeId, $subProcessId): string
    {
        return DB::transaction(function () use ($eventTypeId, $subProcessId) {

            $type = IAndAEventType::lockForUpdate()->findOrFail($eventTypeId);
            $subProcess = SubProcess::lockForUpdate()->findOrFail($subProcessId);

            $count = IncidentAndAccident::where('event_type_id', $eventTypeId)
                ->where('affected_sub_process_id', $subProcessId)
                ->lockForUpdate()
                ->count();

            $consecutive = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            return "{$type->acronym}-{$subProcess->acronym}-{$consecutive}";
        });
    }

    // Comprueba si se puede ver el boton de finalizar el registro de incidentes y accidentes
    public function canViewFinishIncidentAndAccident(IncidentAndAccident $incidentAndAccident)
    {
        if ($incidentAndAccident->status_id !== $this->statusIds['in_execution']) {
            return false;
        }

        // Comprueba si hay acciones asociadas que aún no estén finalizadas
        $hasUnfinishedActions = Action::where('origin_type', IncidentAndAccident::class)
            ->where('origin_id', $incidentAndAccident->id)
            ->where('finished', false)
            ->exists();

        // El botón solo se puede visualizar si no hay acciones sin finalizar
        return ! $hasUnfinishedActions;
    }

    // Cambia el estado de registro de incidentes y accidentes en ejecución si esta reportado
    public function changeIncidentAndAccidentStatusToExecution(IncidentAndAccident $incidentAndAccident): bool
    {
        if ($incidentAndAccident->status_id !== $this->statusIds['reported']) {
            return false;
        }

        return $incidentAndAccident->update(['status_id' => $this->statusIds['in_execution']]);
    }
}
