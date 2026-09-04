<?php

namespace App\Http\Controllers;

use App\Models\Ayuda;
use App\Models\Beca;
use App\Models\Carrera;
use App\Models\Condicion;
use App\Models\Imagen;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BecaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $becas = Beca::paginate(12);
       $universidades = Universidad::get();

       // Cargar estados de interacción para el usuario autenticado
       $postulacionIds = [];
       $guardadoIds = [];

       if (Auth::check() && Auth::user()->role === 'estudiante') {
           $postulacionIds = Auth::user()->postulaciones()->pluck('beca_id')->toArray();
           $guardadoIds    = Auth::user()->becasGuardadas()->pluck('beca_id')->toArray();
       }

       return view('becas.index', compact('becas', 'universidades', 'postulacionIds', 'guardadoIds'));
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

        // Cargar estados de interacción para el usuario autenticado
        $postulacionIds = [];
        $guardadoIds = [];

        if (Auth::check() && Auth::user()->role === 'estudiante') {
            $postulacionIds = Auth::user()->postulaciones()->pluck('beca_id')->toArray();
            $guardadoIds    = Auth::user()->becasGuardadas()->pluck('beca_id')->toArray();
        }

        return view('becas.index', compact('becas', 'universidades', 'postulacionIds', 'guardadoIds'));
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
            'url_oficial' => 'nullable|url'
        ]);

        $imagenId = null;

        if ($request->hasFile('imagenes')) {
            $rutaArchivo = $request->file('imagenes')->store('imagenes', 'public');

            $imagenCreada = Imagen::create([
                'ruta' => $rutaArchivo,
            ]);

            $imagenId = $imagenCreada->id;

        }


        $titulo_en = $this->translateText($data['titulo']);
        $descripcion_en = $this->translateText($data['descripcion']);

        $beca = Beca::create([
            'titulo' => $data['titulo'],
            'titulo_en' => $titulo_en,
            'descripcion' => $data['descripcion'],
            'descripcion_en' => $descripcion_en,
            'universidad_id' => $data['universidad_id'],
            'carrera_id' => $data['carrera_id'],
            'condicion_id' => $data['condicion_id'],
            'vencimiento' => $data['vencimiento'],
            'ayuda_id' => $data['ayuda_id'],
            'imagen_id' => $imagenId,
            'url_oficial' => $data['url_oficial'] ?? null,
            // Translate other nullable fields if they were present in request
            'pais_destino' => $request->pais_destino,
            'pais_destino_en' => $request->pais_destino ? $this->translateText($request->pais_destino) : null,
            'nivel_academico' => $request->nivel_academico,
            'nivel_academico_en' => $request->nivel_academico ? $this->translateText($request->nivel_academico) : null,
            'modalidad' => $request->modalidad ?? 'Presencial',
            'modalidad_en' => $request->modalidad ? $this->translateText($request->modalidad) : 'In-person',
            'cobertura_resumen' => $request->cobertura_resumen,
            'cobertura_resumen_en' => $request->cobertura_resumen ? $this->translateText($request->cobertura_resumen) : null,
        ]);

        return redirect()->route('becas.index')->with('success', 'Beca e imágenes guardadas y traducidas exitosamente.');
    }

    private function translateText($text)
    {
        if (empty($text)) return null;
        
        try {
            $response = Http::get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl' => 'es',
                'tl' => 'en',
                'dt' => 't',
                'q' => $text
            ]);
            
            $json = $response->json();
            $translatedText = '';
            if (isset($json[0]) && is_array($json[0])) {
                foreach ($json[0] as $segment) {
                    if (isset($segment[0])) {
                        $translatedText .= $segment[0];
                    }
                }
            }
            return $translatedText ?: null;
        } catch (\Exception $e) {
            return null; // Fallback silently if translation fails
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $beca = Beca::findOrFail($id);
        $beca->load('universidad');

        // Cargar estados de interacción del usuario
        $postulado = false;
        $guardado  = false;

        if (Auth::check() && Auth::user()->role === 'estudiante') {
            $userId    = Auth::id();
            $postulado = $beca->postulantes()->wherePivot('user_id', $userId)->exists();
            $guardado  = $beca->guardadoPor()->wherePivot('user_id', $userId)->exists();
        }

        return view('becas.detalle', [
            'beca'      => $beca,
            'postulado' => $postulado,
            'guardado'  => $guardado,
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
