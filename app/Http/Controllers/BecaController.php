<?php

namespace App\Http\Controllers;

use App\Models\Ayuda;
use App\Models\Beca;
use App\Models\Carrera;
use App\Models\Condicion;
use App\Models\Imagen;
use App\Models\Universidad;
use Illuminate\Http\Request;

class BecaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $becas = Beca::get();
       $universidades =  Universidad::get();

       return view('becas.index', compact('becas', 'universidades'));

        
    }

    public function filtrar(Request $request)
    {
        // 1. Obtener los datos para los desplegables/selects
        $universidades = Universidad::orderBy('nombre_completo')->get();

        // 2. Construir la consulta dinámica
        $becas = Beca::with('universidad')
            // Filtro por palabra clave (Título o Descripción)
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->where('titulo', 'LIKE', '%' . $request->buscar . '%')
                      ->orWhere('cobertura_resumen', 'LIKE', '%' . $request->buscar . '%');
            })
            // Filtro por Universidad
            ->when($request->filled('universidad_id'), function ($query) use ($request) {
                $query->where('universidad_id', $request->universidad_id);
            })
            // Filtro por Nivel Académico
            ->when($request->filled('nivel_academico'), function ($query) use ($request) {
                $query->where('nivel_academico', $request->nivel_academico);
            })
            // Filtro por Modalidad
            ->when($request->filled('modalidad'), function ($query) use ($request) {
                $query->where('modalidad', $request->modalidad);
            })
            // Solo mostrar becas activas
            ->where('estado', 'Activa')
            ->orderBy('created_at', 'desc')
            // Conservar los parámetros de búsqueda en la paginación
            ->paginate(9)
            ->appends($request->all());

        return view('becas.index', compact('becas', 'universidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('becas.crear', [
            'universidades' => Universidad::get(),
            'carreras' => Carrera::get(),
            'ayuda' => Ayuda::get(),
            'condiciones' => Condicion::get(),
            'imagenes' => Imagen::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'universidad_id' => 'required|exists:universidades,id',
            'carrera_id' => 'required|exists:carreras,id',
            'condicion_id' => 'required|exists:condiciones,id',
            'vencimiento' => 'required|date|after_or_equal:today|date_format:Y-m-d',
            'ayuda_id' => 'required|exists:ayuda,id',
            'imagenes' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagenId = null;

        if ($request->hasFile('imagenes')) {
            $rutaArchivo = $request->file('imagenes')->store('imagenes', 'public');

            $imagenCreada = Imagen::create([
                'ruta' => $rutaArchivo,
            ]);

            $imagenId = $imagenCreada->id;

        }


        $beca = Beca::create([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'universidad_id' => $data['universidad_id'],
            'carrera_id' => $data['carrera_id'],
            'condicion_id' => $data['condicion_id'],
            'vencimiento' => $data['vencimiento'],
            'ayuda_id' => $data['ayuda_id'],
            'imagen_id' => $imagenId,
        ]);

        return redirect()->route('becas.index')->with('success', 'Beca e imágenes guardadas.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $beca = Beca::findOrFail($id);
        $beca->load('universidad');

        return view('becas.detalle', [
            'beca' => $beca,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beca $beca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beca $beca)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beca $beca)
    {
        //
    }
}
