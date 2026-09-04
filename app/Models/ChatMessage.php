<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'contenido',
        'url_adjunto',
        'tipo',
        'reply_to_id',  // Mensaje al que se responde (nullable)
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

    /**
     * Mensaje original al que este mensaje está respondiendo.
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_id')->with('user');
    }

    /**
     * Respuestas que otros mensajes hacen a este.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_id');
    }

    /**
     * Detecta si el contenido es una URL y la guarda como url_adjunto.
     */
    protected static function booted(): void
    {
        static::creating(function (ChatMessage $msg) {
            $urlPattern = '/https?:\/\/[^\s]+/';
            if (preg_match($urlPattern, $msg->contenido, $matches)) {
                $msg->url_adjunto = $matches[0];
                $msg->tipo = 'enlace';
            }
        });
    }
}
