<x-layout>


    <x-slot:titulo>
        Información de la beca
    </x-slot:titulo>
    <x-slot:angosto>
        angosto
    </x-slot:angosto>

    <div class="centrador">


        <h2 class="importante"> Beca {{ $beca->titulo }}</h2>
        <h2>Descripción</h2>
        <p>{{ $beca->descripcion }}</p>

        <h2> Institución</h2>
        <p> {{ $beca->universidad->nombre_completo }}</p>

        @if (isset($beca->carrera->nombre))
            <h2> Carrera</h2>
            <p> {{ $beca->carrera->nombre }}</p>

            <h2> Condiciones</h2>
            <p> {{ $beca->condicion->nombre }}</p>

            <h2> Ayuda</h2>
            <p> {{ $beca->ayuda->nombre }}</p>
        @else
            <h2> Carrera</h2>
            <ul>

                @foreach ($beca->carreras_cobertura as $carrera)
                <li> {{ $carrera }}</li>
                @endforeach
            </ul>
            

            <h2> Nivel académico</h2>
            <p> {{ $beca->nivel_academico }}</p>

            <h2> Modalidad</h2>
            <p> {{ $beca->modalidad }}</p>

            <h2> Cobertura</h2>
            <p> {{ $beca->cobertura_resumen }}</p>

            <h2> Requisitos</h2>
            <ul>

                @foreach ($beca->requisitos as $requisito)
                <li> {{ $requisito }}</li>
                @endforeach
            </ul>

            @if (isset($beca->cum_promedio_minimo))
                <h2> CUM promedio</h2>
                <p> {{ $beca->cum_promedio_minimo }}</p>
            @endif

            <h2> <a href="{{ $beca->url_oficial }}" target="_blank" rel="noopener noreferrer" class="visitar">Visitar sitio oficial</a></h2>
            
        @endif







        @if (isset($beca->imagen->ruta))
            <div style="width: 300px; height: 200px; ">
                <img src="{{ asset('storage/' . $beca->imagen->ruta) }}" alt=""
                    style=" width: 100%; height: 100%;">
            </div>
        @endif
        <br>
        <a href="{{ route('becas.index') }}" class="btn-outline">Volver</a>
    </div>

</x-layout>
