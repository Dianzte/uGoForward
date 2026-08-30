<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
