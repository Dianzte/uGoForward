<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Beca extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titulo', 'titulo_en', 'descripcion', 'descripcion_en', 'universidad_id', 'carrera_id', 'condicion_id', 'vencimiento', 'imagen_id', 'ayuda_id',
        'url_oficial', 'pais_destino', 'pais_destino_en', 'nivel_academico', 'nivel_academico_en', 'modalidad', 'modalidad_en', 'cobertura_resumen', 'cobertura_resumen_en', 'requisitos', 'requisitos_en', 'carreras_cobertura', 'carreras_cobertura_en', 'cum_promedio_minimo', 'estado'
    ];

    protected $casts = [
        'vencimiento' => 'date',
        'requisitos' => 'array',
        'requisitos_en' => 'array',
        'carreras_cobertura' => 'array',
        'carreras_cobertura_en' => 'array',
    ];

    public function universidad(): BelongsTo
    {
        return $this->belongsTo(Universidad::class, 'universidad_id');
    }

    public function imagen(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'imagen_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function condicion(): BelongsTo
    {
        return $this->belongsTo(Condicion::class, 'condicion_id');
    }

    public function ayuda(): BelongsTo
    {
        return $this->belongsTo(Ayuda::class, 'ayuda_id');
    }

    // ── Relaciones de interacción de usuarios ──────────────────────────────

    /**
     * Usuarios que se han postulado a esta beca.
     */
    public function postulantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'postulaciones')
                    ->withPivot('estado', 'postulado_at')
                    ->withTimestamps();
    }

    /**
     * Usuarios que han guardado/favoriteado esta beca.
     */
    public function guardadoPor(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'becas_guardadas')
                    ->withTimestamps();
    }

    /**
     * Sala de chat privada asociada a esta beca.
     */
    public function chatRoom(): HasOne
    {
        return $this->hasOne(ChatRoom::class, 'beca_id');
    }
}
