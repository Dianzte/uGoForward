<x-layout background='becasIndexBackground' seccion="index">
    <x-slot:titulo>
        Becas disponibles

    </x-slot:titulo>
    @push('estilo')
    @vite(['resources/css/becas/index.css'])
    @endpush

    @push('script')
    @vite(['resources/js/becas/index.js'])
    @endpush

    <!--
    
    <div class="contenedorTransparencia">
    <span class="encabezado">Oportunidades</span>
    <div class="tarjetasGrid">

        @foreach ($becas as $beca)
            <div class="tarjeta" id="tarjeta" data-url="{{ route('becas.show', $beca->id) }}">
                <div class="texto" >
                    <p>{{ $beca->titulo }}</p>
                    <p>Última fecha para aplicar: {{ $beca->vencimiento }}</p>
                    <p id="descripcion">{{ $beca->descripcion }}</p>
                </div>
                @if (isset($beca->imagen->ruta))
                    <img src="{{ asset('storage/' . $beca?->imagen->ruta) }}" alt="" class="fondoTarjeta">
                @endif
            </div>
        @endforeach
    </div>

    <div class="contenedorOla">

    </div>

    <a href="{{ route('becas.create') }}" class="enlaceVolver">Crear nueva beca</a>
    -->
    <div>

        


</x-layout>
