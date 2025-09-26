<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Actualiza el vencimiento de los documentos
Schedule::command('statuses:update-doc-deadlines')->dailyAt('00:30');

// Actualiza estados antes de notificar
Schedule::command('statuses:update')->dailyAt('00:10');

// Notifica acciones
Schedule::command('notify:action-limit_dates')->dailyAt('08:00');

// Notifica tareas
Schedule::command('notify:task-limit_dates')->dailyAt('08:10');
