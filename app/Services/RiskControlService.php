<?php

namespace App\Services;

use App\Models\RiskControl;
use Filament\Notifications\Notification;

class RiskControlService
{
    public function updateQualities(RiskControl $riskControl, array $data)
    {
        // dd($riskControl, $data);
        if ($riskControl->control_qualification_id === $data['control_qualification_id']) {
            return false;
        }

        return $riskControl->update(['control_qualification_id' => $data['control_qualification_id']]);
    }

    /* ********************************** */

    // 📌 Se elimina ?
    private function taskNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    } // Se puede utilizar para avisar cuando si se actualize el promediado
}
