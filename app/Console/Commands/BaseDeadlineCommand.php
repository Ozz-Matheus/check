<?php

namespace App\Console\Commands;

use App\Traits\LogsToSchedulerFile;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

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

        // 1. Obtenemos el objeto tenant completo para verificar si existe
        $currentTenant = tenant();
        $tenantId = $currentTenant ? $currentTenant->id : 'Central';

        // Lógica unificada: Busca vencimientos
        $records = $this->getQuery()
            ->whereIn($this->dateColumn, [$today, $warningDate])
            ->get();

        // Agregamos el Tenant al log para saber en qué BD estamos
        $this->logToSchedulerFile("Iniciando revisión ({$this->name}): {$records->count()} registros.");

        // 2. Validación de seguridad: Solo forzamos la URL si estamos en un Tenant real
        if ($currentTenant) {

            $protocol = 'http://';

            $dbSubDomain = $currentTenant->domains->first()?->domain;

            $domain = $dbSubDomain.'.'.config('tenancy.central_domains')[0];

            URL::forceRootUrl($protocol.$domain);
        }

        foreach ($records as $record) {
            $recipients = $this->getRecipients($record);

            foreach ($recipients as $user) {
                if ($user && method_exists($user, 'notify')) {
                    $user->notify($this->getNotification($record));
                    $this->logToSchedulerFile("Notificación enviada a {$user->email} para Documento con ID : {$record->id}.");
                }
            }
        }

        URL::forceRootUrl(null);

        $this->logToSchedulerFile('Finalizó revisión.');

        return Command::SUCCESS;
    }
}
