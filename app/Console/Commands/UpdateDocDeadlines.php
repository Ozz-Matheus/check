<?php

namespace App\Console\Commands;

use App\Models\Doc;
use Illuminate\Console\Command;
use App\Traits\LogsToSchedulerFile;

class UpdateDocDeadlines extends Command
{
    use LogsToSchedulerFile;

    protected $signature = 'statuses:update-doc-deadlines';

    protected $description = 'Actualiza a vencido los documentos vencidos.';

    public function handle(): int
    {
        $this->logToSchedulerFile('Iniciando actualización de Doc vencidos');

        $today = now()->toDateString();

        $expired = Doc::whereDate('central_expiration_date', '<', $today)
            ->where('expiration', false)
            ->update(['expiration' => true]);

        $this->info("Expirados: {$expired}");

        $this->logToSchedulerFile('Finalizó actualización de Doc vencidos');


        return Command::SUCCESS;
    }
}
