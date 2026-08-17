<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioEventoMail;
use App\Models\CalendarioTarea;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosCommand extends Command
{
    /**
     * Nombre y firma del comando Artisan.
     *
     * @var string
     */
    protected $signature = 'recordatorios:enviar';

    /**
     * Descripción del comando que aparece en `php artisan list`.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios por correo electrónico para los eventos próximos del calendario';

    /**
     * Ejecuta el comando de envío de recordatorios.
     *
     * Lógica de negocio:
     *   1. Obtiene las tareas pendientes de recordatorio cuya fecha no haya pasado.
     *   2. Calcula el momento exacto del evento combinando `fecha` + `hora_evento`.
     *      Si no tiene hora, usa medianoche (00:00) como base.
     *   3. Verifica si `now() >= momentoEvento - recordatorio_minutos`.
     *   4. Envía el correo al `correo` del usuario propietario de la tarea.
     *   5. Marca `recordatorio_enviado = true` para evitar duplicados.
     */
    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   uGoForward — Enviando Recordatorios    ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('  Inicio: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $this->info('');

        $ahora = Carbon::now();

        // ── 1. Consultar tareas candidatas ──────────────────────────────────
        // Solo las que no han sido notificadas y cuya fecha sea hoy o futura
        $tareasCandidatas = CalendarioTarea::query()
            ->where('recordatorio_enviado', false)
            ->where('fecha', '>=', $ahora->toDateString())
            ->with('user')           // Eager load para evitar N+1
            ->get();

        $this->line("  Tareas candidatas encontradas: <fg=cyan>{$tareasCandidatas->count()}</>");

        if ($tareasCandidatas->isEmpty()) {
            $this->info('  ✅ No hay recordatorios pendientes.');
            $this->info('');
            return self::SUCCESS;
        }

        $enviados  = 0;
        $omitidos  = 0;
        $errores   = 0;

        // ── 2. Evaluar cada tarea ────────────────────────────────────────────
        foreach ($tareasCandidatas as $tarea) {

            // Construir el Carbon del momento exacto del evento
            $fechaBase  = Carbon::parse($tarea->fecha);
            $horaEvento = $tarea->hora_evento
                ? Carbon::parse($tarea->hora_evento)
                : Carbon::parse('00:00:00');

            $momentoEvento = $fechaBase->copy()->setTime(
                $horaEvento->hour,
                $horaEvento->minute,
                0
            );

            // El momento a partir del cual debemos enviar el correo
            $momentoEnvio = $momentoEvento->copy()->subMinutes($tarea->recordatorio_minutos);

            // ── 3. ¿Es hora de enviar? ─────────────────────────────────────
            if ($ahora->lt($momentoEnvio)) {
                $minutosRestantes = (int) $ahora->diffInMinutes($momentoEnvio, false);
                $this->line("  ⏳ [#{$tarea->id}] '{$tarea->titulo}' — en {$minutosRestantes} min.");
                $omitidos++;
                continue;
            }

            // ── 4. Verificar que el usuario tenga correo ───────────────────
            if (!$tarea->user || empty($tarea->user->correo)) {
                $this->warn("  ⚠️  [#{$tarea->id}] '{$tarea->titulo}' — usuario sin correo, omitido.");
                Log::warning("RecordatoriosCommand: tarea #{$tarea->id} sin email de usuario.", [
                    'tarea_id' => $tarea->id,
                    'user_id'  => $tarea->user_id,
                ]);
                $omitidos++;
                continue;
            }

            // ── 5. Enviar correo ───────────────────────────────────────────
            try {
                Mail::to($tarea->user->correo)
                    ->send(new RecordatorioEventoMail($tarea));

                // ── 6. Marcar como enviado para evitar duplicados ──────────
                $tarea->update(['recordatorio_enviado' => true]);

                $this->info("  ✉️  [#{$tarea->id}] '{$tarea->titulo}' — enviado a {$tarea->user->correo}");

                Log::info('RecordatoriosCommand: Recordatorio enviado.', [
                    'tarea_id'    => $tarea->id,
                    'titulo'      => $tarea->titulo,
                    'destinatario'=> $tarea->user->correo,
                    'momento_evento' => $momentoEvento->toDateTimeString(),
                ]);

                $enviados++;

            } catch (\Throwable $e) {
                $this->error("  ❌ [#{$tarea->id}] Error al enviar: " . $e->getMessage());

                Log::error('RecordatoriosCommand: Fallo al enviar recordatorio.', [
                    'tarea_id' => $tarea->id,
                    'error'    => $e->getMessage(),
                    'trace'    => $e->getTraceAsString(),
                ]);

                $errores++;
            }
        }

        // ── 7. Resumen final ─────────────────────────────────────────────────
        $this->info('');
        $this->line('  ───────────────────────────────────────');
        $this->info("  ✅ Enviados  : <fg=green>{$enviados}</>");
        $this->line("  ⏳ Omitidos  : <fg=yellow>{$omitidos}</>");
        $this->error("  ❌ Errores   : {$errores}");
        $this->line('  ───────────────────────────────────────');
        $this->info('');

        return self::SUCCESS;
    }
}
