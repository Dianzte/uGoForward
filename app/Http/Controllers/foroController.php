<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CategoriasForo;
use App\Models\Foro;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $foros = Foro::with('comentarios')->get();

        $mostrar = Foro::find(1);

        return view('foro.index', [
            'foros' => $foros,
            'ejemplo' => $mostrar,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('foro.create', [
            'universidades' => Universidad::get(),
            'carreras' => Carrera::get(),
            'categorias' => CategoriasForo::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string|max:2000',
            'universidad_id' => 'nullable|exists:universidades,id',
            'carrera_id' => 'nullable|exists:carreras,id',
            'categoriaforo_id' => 'required|exists:categoriasForo,id',
        ]);
        

        Auth()->user()->foros()->create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'universidad_id' => $request->universidad_id,
            'carrera_id' => $request->carrera_id,
            'categoriaforo_id' => $request->categoriaforo_id,
        ]);

        return redirect()->route('foro.index')->with('success', 'Foro creado con éxito.');

    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $foros = Foro::with('comentarios')->get();
        /* $foros->load(['comentariosPrincipales.user', 'comentariosPrincipales.respuestas.user']); */

        $seleccionado = Foro::where('slug', $slug)->firstOrFail();
        $seleccionado->load(['comentarios']);
        $sessionKey = 'foro_visto_'.$seleccionado->id;

        if (! Session::has($sessionKey)) {
            $seleccionado->increment('visitas_count');

            Session::put($sessionKey, true);
        }

        return view('foro.index', [
            'ejemplo' => $seleccionado,
            'foros' => $foros,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
