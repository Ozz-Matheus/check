<?php

namespace App\Services;

use App\Models\ActionEnding;
use App\Models\ActionType;

/**
 * Servicio para las acciones
 */
class ActionEndingService
{
    protected array $statusIds;

    public function __construct(StatusService $statusService)
    {
        $this->statusIds = $statusService->getActionAndTaskStatuses();
    }

    // Comprueba si se puede ver el boton de calificar la efectividad de la accion
    public function canViewQualifyAction(ActionEnding $actionEnding)
    {
        $typeCorrectiveId = ActionType::where('name', 'corrective')->first()?->id;

        return $actionEnding->action->action_type_id === $typeCorrectiveId && ! filled($actionEnding->effectiveness);
    }

    // Cambia el estado de la accion
    public function changeActionStatusToFinish(ActionEnding $actionEnding): bool
    {
        $updates = [
            'finished' => true,
        ];

        if ($actionEnding->action->status_id === $this->statusIds['overdue']) {
            $updates['status_id'] = $this->statusIds['extemporaneous'];
        } else {
            $updates['status_id'] = $this->statusIds['completed'];
        }

        return $actionEnding->action->update($updates);
    }
}
