<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirige al usuario autenticado a la pantalla de selección de rol
 * mientras su columna `role` sea null. Deja pasar libremente las rutas
 * de selección de rol y logout para evitar loops de redirección.
 *
 * Como esto se evalúa en cada request (no hay caché de sesión aquí),
 * si el usuario ya seleccionó su rol y presiona "atrás" en el navegador,
 * cualquier petición nueva a una ruta protegida lo dejará pasar con
 * normalidad porque $user->role ya no es null.
 */
class EnsureRoleIsSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && is_null($user->role) && !$request->routeIs('rol.seleccionar', 'rol.guardar', 'logout')) {
            return redirect()->route('rol.seleccionar');
        }

        return $next($request);
    }
}