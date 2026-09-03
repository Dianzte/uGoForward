<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'tipo',
        'icono',
        'activa',
        'beca_id',
        'owner_id',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id')->latest();
    }

    public function ultimoMensaje()
    {
        return $this->hasOne(ChatMessage::class, 'room_id')->latest();
    }

    /**
     * Beca asociada a esta sala (solo para tipo 'beca_directa').
     */
    public function beca(): BelongsTo
    {
        return $this->belongsTo(Beca::class, 'beca_id');
    }

    /**
     * Estudiante que inició el chat de beca.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
