<?php

namespace App\Http\Controllers;

use App\Models\Foro;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Foro $ejemplo)
    {
        
        $data = $request->validate([
            'contenido' => 'required|max:5000|min:1',
            'padre_id' => 'nullable|exists:comentarios,id'
        ]);

        $ejemplo->comentarios()->create([
            'contenido' => $request->contenido,
            'user_id' => 1, // hay que arreglar esto !!!
            'padre_id' => $request->padre_id ?? null
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
