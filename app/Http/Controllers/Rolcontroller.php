<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    /**
     * Muestra la pantalla de selección de rol.
     * Si el usuario ya tiene un rol asignado, lo redirige a su destino correspondiente
     * en lugar de dejarlo elegir de nuevo.
     */
    public function seleccionar()
    {
        $user = Auth::user();

        if ($user && $user->role) {
            return redirect()->route($this->destinoParaRol($user->role));
        }

        return view('seleccionar-rol');
    }

    /**
     * Guarda el rol elegido por el usuario. Esta acción solo puede ejecutarse
     * una vez: si el usuario ya tiene rol, se ignora el intento y se le redirige
     * a donde ya le corresponde.
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'role' => 'required|in:estudiante,padrino',
        ]);

        $user = Auth::user();

        if ($user->role) {
            return redirect()->route($this->destinoParaRol($user->role));
        }

        $user->role = $request->input('role');
        if (isset($user->role_selected_at)) {
            $user->role_selected_at = now();
        }
        $user->save();

        return redirect()
            ->route($this->destinoParaRol($user->role))
            ->with('status', '¡Rol guardado correctamente! Bienvenido/a a UGF.');
    }

    private function destinoParaRol(string $rol): string
    {
        return $rol === 'estudiante' ? 'test.socioemocional' : 'padrino.tutorial';
    }
}