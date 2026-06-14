<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use Illuminate\Http\Request;
use App\Models\Universidad;
use App\Models\Carrera;
use App\Models\Ayuda;
use App\Models\Condicion;
use App\Models\Imagen;

class BecaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $becas = beca::get();

    return view('becas.index', compact('becas'));
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
            'imagen_id' => 'required|exists:imagenes,id',
            'ayuda_id' => 'required|exists:ayuda,id',
        ]);

        
        Beca::create($data);
        return redirect()->route('becas.index');
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
