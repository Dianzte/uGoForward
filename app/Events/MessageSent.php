<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ChatMessage $message,
        public string $roomSlug,
    ) {}

    /**
     * Canal de broadcasting: chat.{slug}
     * Canal público para que estudiantes puedan escuchar sin autenticación especial.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("chat.{$this->roomSlug}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Datos que se enviarán al cliente via WebSocket.
     */
    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'contenido'   => $this->message->contenido,
            'url_adjunto' => $this->message->url_adjunto,
            'tipo'        => $this->message->tipo,
            'reply_to_id' => $this->message->reply_to_id,
            'created_at'  => $this->message->created_at->toISOString(),
            'user' => [
                'id'     => $this->message->user->id,
                'nombre' => $this->message->user->nombre,
                'avatar' => $this->message->user->avatar,
            ],
            // Datos completos del mensaje citado (null si no es respuesta)
            'reply_to' => $this->message->replyTo ? [
                'id'        => $this->message->replyTo->id,
                'contenido' => $this->message->replyTo->contenido,
                'user'      => [
                    'nombre' => $this->message->replyTo->user?->nombre ?? 'Usuario',
                ],
            ] : null,
        ];
    }
}
