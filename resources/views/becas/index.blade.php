<x-layout background='becas-index-background'>
    <x-slot:titulo>
        Becas disponibles

    </x-slot:titulo>

    <div class="tarjetas-grid">

        @foreach ($becas as $beca)
        <a href="{{ route('becas.show', $beca->id) }}">
            <div class="tarjeta">
                <h2>{{ $beca->titulo }}</h2>
                <p>{{ $beca->descripcion }}</p>
                <p>Última fecha para aplicar: {{ $beca->vencimiento }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <a href="{{ route('becas.create') }}">Crear nueva beca</a>
</x-layout>
