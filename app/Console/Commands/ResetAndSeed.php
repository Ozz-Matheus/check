<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ResetAndSeed extends Command
{
    protected $signature = 'project:reset-and-seed';

    protected $description = 'Reinicia base de datos, genera permisos, limpia cachés y corre Pint.';

    protected string $customLogPath;

    public function handle(): int
    {
        $this->customLogPath = storage_path('logs/dev-reset.log');

        file_put_contents($this->customLogPath, '');

        $this->logBoth('🚀 [ResetAndSeed] Inicio del comando por usuario: '.optional(auth()->user())->email);

        $this->newLine();
        $this->info('🔥 Iniciando limpieza y reseteo del proyecto...');

        $commands = [
            ['php', 'artisan', 'migrate:fresh'],
            ['php', 'artisan', 'db:seed', '--class=AdminSeeder'],
            ['php', 'artisan', 'shield:generate', '--all', '--panel=admin'],
            ['php', 'artisan', 'shield:generate', '--all', '--panel=dashboard'],
            ['php', 'artisan', 'filament:optimize-clear'],
            ['php', 'artisan', 'view:clear'],
            ['php', 'artisan', 'route:clear'],
            ['php', 'artisan', 'optimize:clear'],
            ['php', 'artisan', 'cache:clear'],
            ['php', 'artisan', 'config:clear'],
            ['php', 'artisan', 'config:cache'],
        ];

        foreach ($commands as $command) {
            $label = implode(' ', $command);
            $this->comment(">> Ejecutando: {$label}");
            $this->logBoth("[ResetAndSeed] Ejecutando: {$label}");

            $process = new Process($command);
            $process->setTimeout(300);
            $process->run();

            if (! $process->isSuccessful()) {
                $this->error("❌ Error al ejecutar: {$label}");
                $this->warn(trim($process->getErrorOutput()));

                $this->logBoth("[ResetAndSeed] ❌ Falló: {$label}");
                $this->logBoth(trim($process->getErrorOutput()));

                return self::FAILURE;
            }

            $output = trim($process->getOutput());
            if ($output) {
                $this->line($output);
                $this->logBoth($output);
            }

            $this->info("✅ Completado: {$label}");
            $this->logBoth("[ResetAndSeed] ✅ Completado: {$label}");
            $this->newLine();
        }

        $this->info('🎉 Proyecto reseteado y formateado correctamente.');
        $this->logBoth('✅ [ResetAndSeed] Comando finalizado correctamente');

        return self::SUCCESS;
    }

    protected function logBoth(string $message): void
    {
        file_put_contents($this->customLogPath, now().' '.$message.PHP_EOL, FILE_APPEND);
    }
}
