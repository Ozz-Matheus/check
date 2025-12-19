<?php

namespace App\Console\Commands;

use App\Traits\LogsToSchedulerFile;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

abstract class BaseDeadlineCommand extends Command
{
    use LogsToSchedulerFile;

    /**
     * Define la consulta base al modelo (ej: Action::query()).
     */
    abstract protected function getQuery(): Builder;

    /**
     * Define quiénes reciben la notificación. Debe retornar un array de usuarios.
     */
    abstract protected function getRecipients(Model $record): array;

    /**
     * Define la instancia de la notificación a enviar.
     */
    abstract protected function getNotification(Model $record): mixed;

    /**
     * Nombre de la columna de fecha en la BD.
     */
    protected string $dateColumn = 'limit_date';

    public function handle(): int
    {

        $today = Carbon::today()->toDateString();
        $warningDate = Carbon::today()->addDays(10)->toDateString();

        // Intenta obtener el tenant actual (si existe) para el log
        $tenantId = tenant('id') ?? 'Central';

        // Lógica unificada: Busca vencimientos
        $records = $this->getQuery()
            ->whereIn($this->dateColumn, [$today, $warningDate])
            ->get();

        // Agregamos el Tenant al log para saber en qué BD estamos
        $this->logToSchedulerFile("[{$tenantId}] Iniciando revisión ({$this->name}): {$records->count()} registros.");

        foreach ($records as $record) {
            $recipients = $this->getRecipients($record);

            foreach ($recipients as $user) {
                if ($user && method_exists($user, 'notify')) {
                    $user->notify($this->getNotification($record));
                    $this->info("Notificación enviada a {$user->email} para ID {$record->id}");
                }
            }
        }

        $this->logToSchedulerFile("[Tenant: {$tenantId}] Finalizó revisión.");

        return Command::SUCCESS;
    }
}
