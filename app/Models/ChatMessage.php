<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'contenido',
        'url_adjunto',
        'tipo',
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
