<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Lista todas las salas de chat activas.
     */
    public function index()
    {
        $rooms = ChatRoom::where('activa', true)
            ->with('ultimoMensaje.user')
            ->get();

        return view('hub.chat.index', compact('rooms'));
    }

    /**
     * Muestra una sala de chat con sus mensajes históricos.
     */
    public function show(ChatRoom $room)
    {
        abort_if(!$room->activa, 404);

        // Cargar los últimos 50 mensajes en orden cronológico
        $messages = $room->messages()
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $rooms = ChatRoom::where('activa', true)->get();

        return view('hub.chat.room', compact('room', 'messages', 'rooms'))
            ->with('currentRoom', $room);
    }

    /**
     * Almacena y difunde un nuevo mensaje de chat.
     */
    public function store(Request $request, ChatRoom $room)
    {
        abort_if(!$room->activa, 403, 'Esta sala no está disponible.');

        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'room_id'   => $room->id,
            'user_id'   => Auth::id(),
            'contenido' => $validated['contenido'],
        ]);

        $message->load('user');

        // Disparar evento de WebSocket
        broadcast(new MessageSent($message, $room->slug));

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $message->id,
                'contenido'   => $message->contenido,
                'url_adjunto' => $message->url_adjunto,
                'tipo'        => $message->tipo,
                'created_at'  => $message->created_at->toISOString(),
                'user' => [
                    'id'     => $message->user->id,
                    'nombre' => $message->user->nombre,
                    'avatar' => $message->user->avatar,
                ],
            ],
        ]);
    }
}
