<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Beca::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
        ]);
        return redirect()->route('becas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $beca = Beca::findOrFail($id);
        return view('becas.detalle', compact('beca'));
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
