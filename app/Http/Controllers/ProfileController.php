<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();

        // Si el usuario no ha seleccionado rol, redirigir a la vista de selección
        if (is_null($user->role)) {
            return redirect()->route('rol.seleccionar');
        }

        // Si ya seleccionó rol, cargar la vista de perfil/ajustes
        return view('settings', compact('user'));
    }
}