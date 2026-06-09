<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use Illuminate\Http\Request;
use App\Models\Universidad;

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
