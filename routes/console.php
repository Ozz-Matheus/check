<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notify:action-deadlines')->dailyAt('08:00');
Schedule::command('notify:task-deadlines')->dailyAt('08:10');

Schedule::command('backup:clean')->dailyAt('05:59')->appendOutputTo(storage_path('logs/backup.log'));
Schedule::command('backup:run')->dailyAt('06:00')->appendOutputTo(storage_path('logs/backup.log'));
