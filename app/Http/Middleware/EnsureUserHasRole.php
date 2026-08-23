<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Si el usuario no ha elegido rol y NO está intentando guardar el rol,
        // lo dejamos pasar hacia la vista del perfil (donde le saldrá el selector)
        if ($user && is_null($user->role) && !$request->routeIs('profile.show', 'profile.save-role')) {
            return redirect()->route('profile.show');
        }

        return $next($request);
    }
}