<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Muestra el feed de aportes académicos con filtros.
     */
    public function index(Request $request)
    {
        $query = Post::with('user')->latest();

        if ($request->filled('materia')) {
            $query->porMateria($request->materia);
        }

        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }

        if ($request->filled('q')) {
            $busqueda = $request->q;
            $query->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'like', "%{$busqueda}%")
                  ->orWhere('contenido', 'like', "%{$busqueda}%");
            });
        }

        $posts = $query->paginate(10);

        // Materias disponibles para el filtro
        $materias = Post::select('materia')
            ->whereNotNull('materia')
            ->distinct()
            ->orderBy('materia')
            ->pluck('materia');

        return view('hub.feed.index', compact('posts', 'materias'));
    }

    /**
     * Almacena un nuevo aporte académico.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:200',
            'contenido'   => 'required|string|max:5000',
            'tipo'        => 'required|in:resumen,enlace,guia,tip',
            'materia'     => 'nullable|string|max:100',
            'etiquetas'   => 'nullable|string|max:200',
            'url_adjunto' => 'nullable|url|max:500',
        ]);

        // Convertir etiquetas de texto a array
        if (!empty($validated['etiquetas'])) {
            $validated['etiquetas'] = array_map(
                fn($tag) => ltrim(trim($tag), '#'),
                explode(',', $validated['etiquetas'])
            );
        }

        $validated['user_id'] = Auth::id();

        $post = Post::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'post'    => $post->load('user'),
            ]);
        }

        return back()->with('success', '¡Aporte publicado con éxito!');
    }

    /**
     * Registra o elimina el upvote de un post (toggle).
     */
    public function upvote(Request $request, Post $post)
    {
        $userId = Auth::id();

        if ($post->yaVotadoPor($userId)) {
            // Quitar voto
            $post->upvotedBy()->detach($userId);
            $post->decrement('upvotes_count');
            $votado = false;
        } else {
            // Agregar voto
            $post->upvotedBy()->attach($userId);
            $post->increment('upvotes_count');
            $votado = true;
        }

        return response()->json([
            'votado'       => $votado,
            'upvotes_count' => $post->fresh()->upvotes_count,
        ]);
    }

    /**
     * Almacena un comentario en un post.
     */
    public function comentar(Request $request, Post $post)
    {
        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $comentario = PostComentario::create([
            'post_id'   => $post->id,
            'user_id'   => Auth::id(),
            'contenido' => $validated['contenido'],
        ]);

        return response()->json([
            'success'    => true,
            'comentario' => $comentario->load('user'),
        ]);
    }
}
