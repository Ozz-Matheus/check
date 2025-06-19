<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Notifications\ActionDeadlineNotice;
use App\Traits\LogsToSchedulerFile;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NotifyActionDeadlines extends Command
{
    use LogsToSchedulerFile;

    protected $signature = 'notify:action-deadlines';

    protected $description = 'Notifica a los responsables de acciones de mejora que vencen hoy o en 10 días.';

    public function handle(): int
    {
        $today = Carbon::today();
        $inTenDays = Carbon::today()->addDays(10);

        $acciones = Action::whereIn('deadline', [$today->toDateString(), $inTenDays->toDateString()])->get();

        $this->logToSchedulerFile('Iniciando revisión de acciones con vencimiento');

        foreach ($acciones as $accion) {
            if ($accion->responsibleBy) {
                $accion->responsibleBy->notify(new ActionDeadlineNotice($accion));
                $this->info("Notificación enviada a {$accion->responsibleBy->email} para acción ID {$accion->id}");
            }
        }

        $this->logToSchedulerFile('Finalizó revisión de acciones');

        return Command::SUCCESS;
    }
}
