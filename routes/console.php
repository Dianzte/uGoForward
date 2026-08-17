<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes (Artisan Commands & Scheduler)
|--------------------------------------------------------------------------
|
| Este archivo registra los comandos de consola y define la programación
| de tareas automáticas con el Task Scheduler de Laravel.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Sistema de Recordatorios ─────────────────────────────────────────────────
//
// Ejecuta el comando cada minuto para verificar y enviar recordatorios
// de eventos del calendario a los usuarios correspondientes.
//
// Para probar en local:   php artisan schedule:work
// Para producción (cron): * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
//
Schedule::command('recordatorios:enviar')
    ->everyMinute()
    ->withoutOverlapping()      // Evita ejecuciones paralelas si tarda más de 1 min
    ->runInBackground()         // No bloquea el proceso del scheduler
    ->appendOutputTo(storage_path('logs/recordatorios.log'));  // Log dedicado

