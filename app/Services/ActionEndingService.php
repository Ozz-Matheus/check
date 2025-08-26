<?php

namespace App\Services;

use App\Models\ActionEnding;
use App\Models\ActionType;

/**
 * Servicio para las acciones
 */
class ActionEndingService
{
    public function canViewQualifyAction(ActionEnding $actionEnding)
    {
        $typeCorrectiveId = ActionType::where('name', 'corrective')->first()?->id;

        return $actionEnding->action->action_type_id === $typeCorrectiveId && ! filled($actionEnding->effectiveness);
    }
}
