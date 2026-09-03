<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use App\Models\BecaGuardada;
use Illuminate\Support\Facades\Auth;

class BecaGuardadaController extends Controller
{
    /**
     * Alterna el estado de guardado de una beca para el usuario autenticado.
     * Si no está guardada → la guarda. Si ya está guardada → la elimina.
     */
    public function toggle(Beca $beca)
    {
        $user = Auth::user();

        $existente = BecaGuardada::where('user_id', $user->id)
                                 ->where('beca_id', $beca->id)
                                 ->first();

        if ($existente) {
            // Ya está guardada → quitar de favoritos
            $existente->delete();

            return response()->json([
                'guardado' => false,
                'mensaje'  => 'Beca eliminada de tus favoritos.',
            ]);
        }

        // No está guardada → agregar a favoritos
        BecaGuardada::create([
            'user_id' => $user->id,
            'beca_id' => $beca->id,
        ]);

        return response()->json([
            'guardado' => true,
            'mensaje'  => 'Beca guardada en tus favoritos.',
        ]);
    }
}
