<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostulacionController extends Controller
{
    /**
     * Registra la postulación del usuario autenticado a una beca.
     * Solo disponible para usuarios con role = 'estudiante'.
     */
    public function store(Beca $beca)
    {
        $user = Auth::user();

        // Verificar rol de estudiante
        if ($user->role !== 'estudiante') {
            return response()->json([
                'error' => 'Solo los estudiantes pueden postularse a becas.',
            ], 403);
        }

        // Verificar que la beca esté activa
        if ($beca->estado !== 'Activa') {
            return response()->json([
                'error' => 'Esta beca no está disponible.',
            ], 422);
        }

        // Verificar si ya se ha postulado (manejo de duplicado)
        $yaPostulado = Postulacion::where('user_id', $user->id)
                                  ->where('beca_id', $beca->id)
                                  ->exists();

        if ($yaPostulado) {
            return response()->json([
                'postulado' => true,
                'mensaje'   => 'Ya estás postulado a esta beca.',
            ]);
        }

        // Registrar la postulación
        Postulacion::create([
            'user_id' => $user->id,
            'beca_id' => $beca->id,
            'estado'  => 'pendiente',
        ]);

        return response()->json([
            'postulado' => true,
            'mensaje'   => '¡Postulación enviada con éxito!',
        ]);
    }

    /**
     * Cancela la postulación del usuario (solo si sigue en estado pendiente).
     */
    public function destroy(Beca $beca)
    {
        $user = Auth::user();

        Postulacion::where('user_id', $user->id)
                   ->where('beca_id', $beca->id)
                   ->where('estado', 'pendiente')
                   ->delete();

        return response()->json([
            'postulado' => false,
            'mensaje'   => 'Postulación cancelada.',
        ]);
    }
}
