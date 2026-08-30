<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'contenido',
        'tipo',
        'materia',
        'etiquetas',
        'url_adjunto',
        'upvotes_count',
        'comentarios_count',
    ];

    protected $casts = [
        'etiquetas' => 'array',
        'upvotes_count' => 'integer',
        'comentarios_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comentarios(): HasMany
    {
        // Usa la tabla dedicada post_comentarios (NO la tabla comentarios del Foro)
        return $this->hasMany(PostComentario::class)->latest();
    }

    public function upvotedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_upvotes')->withTimestamps();
    }

    /**
     * Verifica si el usuario dado ya votó este post.
     */
    public function yaVotadoPor(int $userId): bool
    {
        return $this->upvotedBy()->where('user_id', $userId)->exists();
    }

    /**
     * Scope para filtrar por materia.
     */
    public function scopePorMateria($query, string $materia)
    {
        return $query->where('materia', $materia);
    }

    /**
     * Scope para filtrar por tipo.
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
