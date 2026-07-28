<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beca;
use App\Models\CalendarioTarea;
use Carbon\Carbon;

class BecaCalendarioController extends Controller
{
    /**
     * Helper to obtain or create a valid user ID for tasks.
     */
    private function resolveUserId()
    {
        if (auth()->check()) {
            return auth()->id();
        }

        try {
            $user = \App\Models\User::first();
            if (!$user) {
                // Si no hay usuarios en la BD, se crea uno predeterminado para pruebas con la estructura correcta
                $user = \App\Models\User::create([
                    'usuario'      => 'invitado',
                    'nombre'       => 'Usuario Invitado',
                    'correo'       => 'invitado@ugoforward.com',
                    'contrasena'   => \Illuminate\Support\Facades\Hash::make('password123'),
                    'fechaNac'     => '2000-01-01',
                    'departamento' => 'San Salvador',
                ]);
            }
            return $user->id;
        } catch (\Throwable $e) {
            \Log::error("Error al obtener o crear usuario para el calendario: " . $e->getMessage());
            // Intenta obtener cualquier usuario existente sin lanzar excepción
            $user = \App\Models\User::first();
            return $user ? $user->id : null;
        }
    }

    public function obtenerEventos(Request $request)
    {
        $eventos = [];

        // 1. BECAS QUEMADAS (DATOS FIJOS DE PRUEBA)
        // Fechas dinámicas basadas en la fecha actual para que SIEMPRE aparezcan en el mes visible
        $now = Carbon::now();
        $becasQuemadas = [
            [
                'id'          => 'beca_fija_1',
                'titulo'      => 'Beca Fantel - Postgrados',
                'vencimiento' => $now->copy()->addDays(4)->format('Y-m-d'),
                'descripcion' => 'Beca del gobierno para maestrías y doctorados en el extranjero.',
                'categoria'   => 'Maestría'
            ],
            [
                'id'          => 'beca_fija_2',
                'titulo'      => 'Beca Fundación Carolina',
                'vencimiento' => $now->copy()->addDays(12)->format('Y-m-d'),
                'descripcion' => 'Becas para estudios de postgrado en España.',
                'categoria'   => 'Postgrado'
            ],
            [
                'id'          => 'beca_fija_3',
                'titulo'      => 'Beca Fulbright - USA',
                'vencimiento' => $now->copy()->addDays(25)->format('Y-m-d'),
                'descripcion' => 'Programa de becas para estudios en Estados Unidos.',
                'categoria'   => 'Maestría / Doctorado'
            ],
        ];

        foreach ($becasQuemadas as $beca) {
            $fechaVencimiento = Carbon::parse($beca['vencimiento']);
            $diasRestantes    = now()->startOfDay()->diffInDays($fechaVencimiento, false);

            if ($diasRestantes < 0) {
                $color = '#6B7280'; // Gris (Vencida)
            } elseif ($diasRestantes <= 5) {
                $color = '#EF4444'; // Rojo (Urgente)
            } elseif ($diasRestantes <= 15) {
                $color = '#F59E0B'; // Amarillo (Próxima)
            } else {
                $color = '#10B981'; // Verde (Con tiempo)
            }

            $eventos[] = [
                'id'            => $beca['id'],
                'title'         => '🎓 ' . $beca['titulo'],
                'start'         => $beca['vencimiento'],
                'color'         => $color,
                'editable'      => false,
                'extendedProps' => [
                    'tipo'        => 'beca_fija',
                    'descripcion' => $beca['descripcion'],
                    'categoria'   => $beca['categoria']
                ]
            ];
        }

        // 2. LEER LAS BECAS DE BASE DE DATOS (Si existen)
        try {
            if ($request->start && $request->end) {
                $becas = Beca::whereBetween('vencimiento', [$request->start, $request->end])->get();
            } else {
                $becas = Beca::all();
            }

            foreach ($becas as $beca) {
                if (!$beca->vencimiento) continue;
                $fechaVencimiento = Carbon::parse($beca->vencimiento);
                $diasRestantes    = now()->startOfDay()->diffInDays($fechaVencimiento, false);

                if ($diasRestantes < 0) {
                    $color = '#6B7280';
                } elseif ($diasRestantes <= 5) {
                    $color = '#EF4444';
                } elseif ($diasRestantes <= 15) {
                    $color = '#F59E0B';
                } else {
                    $color = '#10B981';
                }

                $eventos[] = [
                    'id'            => 'beca_' . $beca->id,
                    'title'         => '🎓 ' . $beca->titulo,
                    'start'         => $fechaVencimiento->format('Y-m-d'),
                    'color'         => $color,
                    'extendedProps' => [
                        'tipo'        => 'beca',
                        'descripcion' => $beca->descripcion ?? 'Sin descripción',
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Si falla la BD de becas, no interrumpe los eventos fijados ni las tareas
            \Log::warning("Error consultando becas: " . $e->getMessage());
        }

        // 3. LEER TAREAS DE AGENDA DE LA BASE DE DATOS
        try {
            $userId = $this->resolveUserId();

            if ($userId) {
                $query = CalendarioTarea::where('user_id', $userId);
                if ($request->start && $request->end) {
                    $query->whereBetween('fecha', [$request->start, $request->end]);
                }
                $tareas = $query->get();

                foreach ($tareas as $tarea) {
                    $eventos[] = [
                        'id'            => 'tarea_' . $tarea->id,
                        'title'         => '📌 ' . $tarea->titulo,
                        'start'         => Carbon::parse($tarea->fecha)->format('Y-m-d'),
                        'color'         => '#3B82F6', // Azul para la agenda
                        'extendedProps' => [
                            'tipo'       => 'tarea',
                            'tarea_id'   => $tarea->id,
                            'completado' => $tarea->completado
                        ]
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::warning("Error consultando tareas del calendario: " . $e->getMessage());
        }

        return response()->json($eventos);
    }

    public function guardarTarea(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'fecha'  => 'required|date',
            ]);

            $userId = $this->resolveUserId();

            $tarea = CalendarioTarea::create([
                'user_id' => $userId,
                'titulo'  => $request->titulo,
                'fecha'   => $request->fecha,
            ]);

            return response()->json(['success' => true, 'tarea' => $tarea]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'error'   => implode(', ', array_merge(...array_values($ve->errors())))
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Modificar una tarea existente
     */
    public function actualizarTarea(Request $request, $id)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'fecha'  => 'required|date',
            ]);

            $userId = $this->resolveUserId();

            $tarea = CalendarioTarea::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $tarea->update([
                'titulo' => $request->titulo,
                'fecha'  => $request->fecha,
            ]);

            return response()->json(['success' => true, 'tarea' => $tarea]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una tarea existente
     */
    public function eliminarTarea($id)
    {
        try {
            $userId = $this->resolveUserId();

            $tarea = CalendarioTarea::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $tarea->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}