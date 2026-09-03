<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BecaChatController extends Controller
{
    /**
     * Obtiene o crea la sala de chat privada para una beca.
     * Devuelve el slug de la sala y los últimos 30 mensajes.
     */
    public function getOrCreate(Beca $beca)
    {
        $user = Auth::user();

        // Buscar sala existente para esta beca y este usuario
        $room = ChatRoom::where('beca_id', $beca->id)
                        ->where('owner_id', $user->id)
                        ->first();

        if (!$room) {
            // Generar un slug único: beca-{id}-user-{userId}
            $slug = 'beca-' . $beca->id . '-user-' . $user->id;

            $room = ChatRoom::create([
                'nombre'      => 'Chat Beca: ' . Str::limit(strip_tags($beca->titulo ?? ''), 40),
                'slug'        => $slug,
                'descripcion' => 'Chat privado entre estudiante y padrino para la beca #' . $beca->id,
                'tipo'        => 'beca_directa',
                'activa'      => true,
                'beca_id'     => $beca->id,
                'owner_id'    => $user->id,
            ]);
        }

        // Cargar los últimos 30 mensajes en orden cronológico
        $messages = ChatMessage::where('room_id', $room->id)
                               ->with('user:id,nombre,avatar')
                               ->latest()
                               ->take(30)
                               ->get()
                               ->reverse()
                               ->values()
                               ->map(function ($msg) use ($user) {
                                   return [
                                       'id'         => $msg->id,
                                       'contenido'  => $msg->contenido,
                                       'mio'        => $msg->user_id === $user->id,
                                       'autor'      => $msg->user->nombre ?? 'Usuario',
                                       'created_at' => $msg->created_at->format('H:i'),
                                   ];
                               });

        return response()->json([
            'room_id'   => $room->id,
            'room_slug' => $room->slug,
            'messages'  => $messages,
        ]);
    }

    /**
     * Envía un mensaje al chat privado de la beca.
     */
    public function sendMessage(Request $request, Beca $beca)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
            'room_id'   => 'required|integer|exists:chat_rooms,id',
        ]);

        // Verificar que la sala pertenece a esta beca
        $room = ChatRoom::where('id', $validated['room_id'])
                        ->where('beca_id', $beca->id)
                        ->firstOrFail();

        $message = ChatMessage::create([
            'room_id'   => $room->id,
            'user_id'   => $user->id,
            'contenido' => $validated['contenido'],
        ]);

        $message->load('user:id,nombre,avatar');

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $message->id,
                'contenido'  => $message->contenido,
                'mio'        => true,
                'autor'      => $message->user->nombre ?? 'Tú',
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }
}
