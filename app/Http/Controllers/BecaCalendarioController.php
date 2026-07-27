<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beca;
use App\Models\CalendarioTarea;
use Carbon\Carbon;

class BecaCalendarioController extends Controller
{
    public function obtenerEventos(Request $request)
    {
        $eventos = [];

        // 1. LEER LAS BECAS (Creadas por tu compañero)
        // Ajusta 'fecha_cierre' o 'nombre' según las columnas que usó tu compañero en su tabla
        $becas = Beca::whereBetween('vencimiento', [$request->start, $request->end])->get();


        // =========================================================
        // AGREGAR DESDE AQUÍ (DATOS QUEMADOS - BECAS NO MODIFICABLES)
        // =========================================================
        $becasQuemadas = [
            [
                'id' => 'beca_fija_1',
                'titulo' => 'Beca Fantel - Postgrados',
                'vencimiento' => '2026-08-15',
                'descripcion' => 'Beca del gobierno para maestrías y doctorados en el extranjero.',
                'categoria' => 'Maestría'
            ],
            [
                'id' => 'beca_fija_2',
                'titulo' => 'Beca Fundación Carolina',
                'vencimiento' => '2026-09-10',
                'descripcion' => 'Becas para estudios de postgrado en España.',
                'categoria' => 'Postgrado'
            ],
            [
                'id' => 'beca_fija_3',
                'titulo' => 'Beca Fulbright - USA',
                'vencimiento' => '2026-10-05',
                'descripcion' => 'Programa de becas para estudios en Estados Unidos.',
                'categoria' => 'Maestría / Doctorado'
            ],
        ];

        foreach ($becasQuemadas as $beca) {
            $fechaVencimiento = Carbon::parse($beca['vencimiento']);
            $diasRestantes = now()->startOfDay()->diffInDays($fechaVencimiento, false);

            if ($diasRestantes < 0) {
                $color = '#6B7280'; // Gris
            } elseif ($diasRestantes <= 5) {
                $color = '#EF4444'; // Rojo
            } elseif ($diasRestantes <= 15) {
                $color = '#F59E0B'; // Amarillo
            } else {
                $color = '#10B981'; // Verde
            }

            $eventos[] = [
                'id' => $beca['id'],
                'title' => '🎓 ' . $beca['titulo'],
                'start' => $beca['vencimiento'],
                'color' => $color,
                'editable' => false, // 🔒 Impide que el usuario la mueva o modifique
                'extendedProps' => [
                    'tipo' => 'beca_fija',
                    'descripcion' => $beca['descripcion'],
                    'categoria' => $beca['categoria']
                ]
            ];
        }
        // =========================================================
        // HASTA AQUÍ
        // =========================================================


        foreach ($becas as $beca) {
            $fechaVencimiento = Carbon::parse($beca->vencimiento);
            $diasRestantes = now()->startOfDay()->diffInDays($fechaVencimiento, false);

            // Semáforo de colores
            if ($diasRestantes < 0) {
                $color = '#6B7280'; // Gris
            } elseif ($diasRestantes <= 5) {
                $color = '#EF4444'; // Rojo
            } elseif ($diasRestantes <= 15) {
                $color = '#F59E0B'; // Amarillo
            } else {
                $color = '#10B981'; // Verde
            }

            $eventos[] = [
                'id' => 'beca_' . $beca->id,
                'title' => '🎓 ' . $beca->titulo, // Columna de su tabla
                'start' => $beca->vencimiento->format('Y-m-d'),  // Columna de su tabla
                'color' => $color,
                'extendedProps' => [
                    'tipo' => 'beca',
                    'descripcion' => $beca->descripcion ?? 'Sin descripción',
                ]
            ];
        }

                // 2. LEER TUS TAREAS DE AGENDA
            // Obtenemos el usuario (el autenticado o el primero que exista en la BD para pruebas)
            $userId = auth()->id() ?? \App\Models\User::first()?->id;

            if ($userId) {
                $tareas = CalendarioTarea::where('user_id', $userId)
                    ->whereBetween('fecha', [$request->start, $request->end])
                    ->get();

                foreach ($tareas as $tarea) {
                    $eventos[] = [
                        'id'    => 'tarea_' . $tarea->id,
                        'title' => ' ' . $tarea->titulo,
                        'start' => Carbon::parse($tarea->fecha)->format('Y-m-d'), // Formato YYYY-MM-DD
                        'color' => '#3B82F6', // Azul para la agenda
                        'extendedProps' => [
                            'tipo'       => 'tarea',
                            'tarea_id'   => $tarea->id,
                            'completado' => $tarea->completado
                        ]
                    ];
                }
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

        // Busca el ID del usuario activo, o el primer usuario que exista en la BD para pruebas
        $userId = auth()->id() ?? \App\Models\User::first()?->id;

        if (!$userId) {
            return response()->json([
                'success' => false, 
                'error'   => 'No hay ningún usuario registrado en la base de datos.'
            ], 400);
        }

        $tarea = CalendarioTarea::create([
            'user_id' => $userId,
            'titulo'  => $request->titulo,
            'fecha'   => $request->fecha,
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
     * Modificar una tarea existente
     */
    public function actualizarTarea(Request $request, $id)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'fecha'  => 'required|date',
            ]);

            $userId = auth()->id() ?? \App\Models\User::first()?->id;

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
            $userId = auth()->id() ?? \App\Models\User::first()?->id;

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