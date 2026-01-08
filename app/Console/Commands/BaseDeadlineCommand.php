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
    abstract protected function getWarningNotification(Model $record): mixed; // Para advertencia días antes.

    abstract protected function getExpiredNotification(Model $record): mixed; // Para vencido.

    /**
     * Nombre de la columna de fecha en la BD.
     */
    protected string $dateColumn = 'limit_date';

    public function handle(): int
    {

        $today = Carbon::today()->toDateString();
        $oneDayBefore = Carbon::today()->addDays(1)->toDateString(); // 1 día antes
        $tenDaysBefore = Carbon::today()->addDays(10)->toDateString(); // 10 días antes

        // 1. Obtenemos el objeto tenant completo para verificar si existe
        $currentTenant = tenant();

        // Lógica unificada: Busca vencimientos que coincidan con cualquiera de las 3 fechas
        $records = $this->getQuery()
            ->whereIn($this->dateColumn, [$today, $oneDayBefore, $tenDaysBefore])
            ->get();

        // Inicio de revisión
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

            // Determinamos la fecha real del registro para saber qué plantilla usar
            $recordDate = Carbon::parse($record->{$this->dateColumn})->toDateString();

            // Lógica de decisión de plantilla
            if ($recordDate === $today) {
                // ES HOY: Plantilla de Vencido
                $notification = $this->getExpiredNotification($record);
            } else {
                // ES 1 o DÍAS ANTES: Plantilla de Próximo a Vencer
                $notification = $this->getWarningNotification($record);
            }

            foreach ($recipients as $user) {
                if ($user && method_exists($user, 'notify')) {
                    $user->notify($notification);
                    $this->logToSchedulerFile("Notificación enviada a {$user->email} para ID: {$record->id} (Fecha: $recordDate).");
                }
            }
        }

        URL::forceRootUrl(null);

        $this->logToSchedulerFile('Finalizó revisión.');

        return Command::SUCCESS;
    }
}
