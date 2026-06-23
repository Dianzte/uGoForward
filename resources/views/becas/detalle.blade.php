<x-layout>
    <x-slot:titulo>
        Buenos días
    </x-slot:titulo>
    
    <h2> Beca {{ $beca->titulo }}</h2>
    <h2>Descripción</h2>
    <p>{{ $beca->descripcion }}</p>

    <h2> Institución</h2>
    <p> {{ $beca->universidad->nombre_completo }}</p>

    <h2> Carrera</h2>
    <p> {{ $beca->carrera->nombre }}</p>

    <h2> Condiciones</h2>
    <p> {{ $beca->condicion->nombre }}</p>

    <h2> Ayuda</h2>
    <p> {{ $beca->ayuda->nombre }}</p>

    @if (isset($beca->imagen->ruta))
        <div style="width: 300px; height: 200px; ">
            <img src="{{ asset('storage/' . $beca->imagen->ruta) }}" alt="Imagen de la beca"
                style=" width: 100%; height: 100%;">
        </div>
    @endif
    <a href="{{ route('becas.index') }}">Volver</a>
</x-layout>
