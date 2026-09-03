<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalApoyo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /**
     * Muestra el tablero de metas académicas.
     * Combina metas públicas de la comunidad con las metas propias del usuario.
     */
    public function index()
    {
        // Metas públicas de la comunidad (excepto las propias)
        $metasComunidad = Goal::publicas()
            ->where('user_id', '!=', Auth::id())
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        // Metas propias del usuario autenticado
        $misMetas = Goal::where('user_id', Auth::id())
            ->with('user')
            ->latest()
            ->get();

        // IDs de metas que el usuario ya apoyó
        $apoyadasIds = GoalApoyo::where('user_id', Auth::id())
            ->pluck('goal_id')
            ->toArray();

        return view('hub.goals.index', compact('metasComunidad', 'misMetas', 'apoyadasIds'));
    }

    /**
     * Crea una nueva meta académica.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'       => 'required|string|max:200',
            'descripcion'  => 'nullable|string|max:1000',
            'es_publica'   => 'boolean',
            'fecha_limite' => 'nullable|date|after:today',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['estado']  = 'en_progreso';
        $validated['progreso'] = 0;

        $goal = Goal::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'goal' => $goal->load('user')]);
        }

        return back()->with('success', '¡Meta creada! ¡Tú puedes lograrlo!');
    }

    /**
     * Actualiza el progreso o estado de una meta.
     */
    public function update(Request $request, Goal $goal)
    {
        // Solo el dueño puede modificar su meta
        abort_if($goal->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'progreso' => 'sometimes|integer|min:0|max:100',
            'estado'   => 'sometimes|in:en_progreso,completada,abandonada',
        ]);

        // Si llega a 100% automáticamente se completa
        if (isset($validated['progreso']) && $validated['progreso'] === 100) {
            $validated['estado'] = 'completada';
        }

        $goal->update($validated);

        return response()->json([
            'success' => true,
            'goal'    => $goal->fresh(),
        ]);
    }

    /**
     * Agrega o quita apoyo a una meta de otro estudiante (toggle).
     */
    public function apoyo(Request $request, Goal $goal)
    {
        $userId = Auth::id();

        // No puedes apoyar tu propia meta
        abort_if($goal->user_id === $userId, 403, 'No puedes apoyar tu propia meta.');

        $apoyo = GoalApoyo::where('user_id', $userId)
            ->where('goal_id', $goal->id)
            ->first();

        if ($apoyo) {
            $apoyo->delete();
            $goal->decrement('apoyos_count');
            $apoyado = false;
        } else {
            GoalApoyo::create([
                'user_id' => $userId,
                'goal_id' => $goal->id,
                'mensaje' => $request->input('mensaje'),
            ]);
            $goal->increment('apoyos_count');
            $apoyado = true;
        }

        return response()->json([
            'apoyado'      => $apoyado,
            'apoyos_count' => $goal->fresh()->apoyos_count,
        ]);
    }
}
