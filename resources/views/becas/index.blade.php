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

    
            <span class="subtitulo">Un mar de oportunidades</span>
            <h1 class="importante">Busca por universidades</h1>

            <div class="destacados">
                <div class="destacado">UCA</div>
                <div class="destacado activo">UDB</div>
                <div class="destacado">UTEC</div>
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
                        <div class="oceanTarjeta">

                            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                                <g id="onda-base2">
                                    <path d="M0,60 Q150,0 300,60 T600,60 T900,60 T1200,60 L1200,120 L0,120 Z"
                                        class="onda ondaTarjeta"></path>
                                </g>


                                <use href="#onda-base2" x="0" y="2" />

                                <use href="#onda-base2" x="-600" y="2" />
                            </svg>
                            <div class="tarjetaPortada"></div>
                        </div>


                    </div>
                @endforeach

            </div>


            <a href="{{ route('becas.create') }}" class="enlaceVolver">Crear nueva beca</a>
        </div>
       
</x-layout>
