<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'estado',
        'progreso',
        'es_publica',
        'fecha_limite',
        'apoyos_count',
    ];

    protected $casts = [
        'es_publica' => 'boolean',
        'fecha_limite' => 'date',
        'progreso' => 'integer',
        'apoyos_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apoyos(): HasMany
    {
        return $this->hasMany(GoalApoyo::class);
    }

    /**
     * Verifica si el usuario ya apoyó esta meta.
     */
    public function yaApoyadoPor(int $userId): bool
    {
        return $this->apoyos()->where('user_id', $userId)->exists();
    }

    /**
     * Scope para metas públicas.
     */
    public function scopePublicas($query)
    {
        return $query->where('es_publica', true);
    }

    /**
     * Scope para metas en progreso.
     */
    public function scopeEnProgreso($query)
    {
        return $query->where('estado', 'en_progreso');
    }
}
