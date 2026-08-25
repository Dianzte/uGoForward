<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarioTarea extends Model
{
    use HasFactory;

    protected $table = 'calendario_tareas';

    /**
     * Campos que se pueden asignar masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'beca_id',
        'titulo',
        'fecha',
        'completado',
        // ── Campos de recordatorio ──
        'email_destinatario',
        'hora_evento',
        'descripcion',
        'recordatorio_minutos',
        'recordatorio_enviado',
    ];

    /**
     * Conversiones de tipo automáticas.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha'                => 'date',
        'completado'           => 'boolean',
        'recordatorio_enviado' => 'boolean',
        'recordatorio_minutos' => 'integer',
    ];

    // ── Relaciones ───────────────────────────────────────────────────────────

    /**
     * Usuario propietario de la tarea.
     * Se usa en EnviarRecordatoriosCommand para obtener el correo destinatario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}