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

    <section>
        <div class="difuminado">
            <span class="encabezado">Oportunidades</span>

            <div class="destacados">
                <div class="">hola</div>
                <div class="">hola</div>
                <div class="">hola</div>
            </div>

            <div>Aquí habra un boton para filtrar</div>

            <div class="indexGrid">
                @foreach ($becas as $beca)
                    <div class="tarjeta" id="tarjeta" data-url="{{ route('becas.show', $beca->id) }}">
                        <div class="texto">
                            <p>{{ $beca->titulo }}</p>
                            <p>Última fecha para aplicar: {{ $beca->vencimiento }}</p>
                            <p id="descripcion">{{ $beca->descripcion }}</p>
                        </div>
                        <div class="tarjetaPortada">
                        </div>
                    </div>
                @endforeach

            </div>


            <a href="{{ route('becas.create') }}" class="enlaceVolver">Crear nueva beca</a>
        </div>
        <div class="contenedorMar">
            <div class="ola"></div>
            <div class="mar"></div>
            </div
    </section>
</x-layout>
