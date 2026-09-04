<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilHubController extends Controller
{
    /**
     * Muestra el perfil público de un usuario del Hub.
     * Si no se especifica usuario, muestra el perfil del autenticado.
     */
    public function show(?User $user = null)
    {
        $user = $user ?? Auth::user();

        $posts = Post::where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        $goalsActivas = Goal::where('user_id', $user->id)
            ->where('estado', 'en_progreso')
            ->take(4)
            ->get();

        $goalsCompletadas = Goal::where('user_id', $user->id)
            ->where('estado', 'completada')
            ->count();

        $totalUpvotes = Post::where('user_id', $user->id)->sum('upvotes_count');

        $esPropio = Auth::id() === $user->id;

        return view('hub.perfil.show', compact(
            'user',
            'posts',
            'goalsActivas',
            'goalsCompletadas',
            'totalUpvotes',
            'esPropio',
        ));
    }

    /**
     * Muestra el formulario de edición del perfil propio.
     */
    public function edit()
    {
        return view('hub.perfil.edit', ['user' => Auth::user()]);
    }

    /**
     * Guarda los cambios del perfil (bio y avatar URL).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'bio'    => 'nullable|string|max:300',
            'avatar' => 'nullable|url|max:500',
            'banner' => 'nullable|url|max:500',
        ]);

        $user->bio = $validated['bio'] ?? null;
        if ($request->has('avatar')) {
            $user->avatar = $validated['avatar'] ?: null;
        }
        if ($request->has('banner')) {
            $user->banner = $validated['banner'] ?: null;
        }
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('¡Perfil actualizado!'),
                'user'    => $user->fresh()
            ]);
        }

        return back()->with('success', __('¡Perfil actualizado!'));
    }
}
