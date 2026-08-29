<x-layout>
    <x-slot:titulo>
        Becas disponibles

    </x-slot:titulo>
    <x-slot:angosto>

    </x-slot:angosto>


    <span class="subtitulo">Un mar de oportunidades</span>
    <h1 class="importante">Explora las becas disponibles</h1>

    
    <div class="filtros">
        <form action="{{ route('becas.filtrar') }}" method="GET" class="filtros">

            <!-- Búsqueda por Texto -->
            <div class="col">
                <label class="form-label font-weight-bold">Buscar por palabra clave</label>
                <input type="text" name="buscar" class="form-control" placeholder="Ej: Excelencia, Cómputo..."
                    value="{{ request('buscar') }}">
            </div>

            <!-- Filtro por Universidad -->
            <div class="col">
                <label class="form-label font-weight-bold">Universidad</label>
                <select name="universidad_id" class="form-select">
                    <option value="">Todas las Universidades</option>
                    @foreach ($universidades as $uni)
                        <option value="{{ $uni->id }}"
                            {{ request('universidad_id') == $uni->id ? 'selected' : '' }}>
                            {{ $uni->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Nivel Académico -->
            <div class="col">
                <label class="form-label font-weight-bold">Nivel</label>
                <select name="nivel_academico" class="form-select">
                    <option value="">Todos</option>
                    <option value="Técnico" {{ request('nivel_academico') == 'Técnico' ? 'selected' : '' }}>Técnico
                    </option>
                    <option value="Licenciatura" {{ request('nivel_academico') == 'Licenciatura' ? 'selected' : '' }}>
                        Licenciatura</option>
                    <option value="Ingeniería" {{ request('nivel_academico') == 'Ingeniería' ? 'selected' : '' }}>
                        Ingeniería</option>
                    <option value="Maestría" {{ request('nivel_academico') == 'Maestría' ? 'selected' : '' }}>
                        Maestría</option>
                </select>
            </div>

            <!-- Filtro por Modalidad -->
            <div class="col">
                <label class="form-label font-weight-bold">Modalidad</label>
                <select name="modalidad" class="form-select">
                    <option value="">Todas</option>
                    <option value="Presencial" {{ request('modalidad') == 'Presencial' ? 'selected' : '' }}>
                        Presencial</option>
                    <option value="Virtual" {{ request('modalidad') == 'Virtual' ? 'selected' : '' }}>Virtual
                    </option>
                    <option value="Híbrida" {{ request('modalidad') == 'Híbrida' ? 'selected' : '' }}>Híbrida
                    </option>
                </select>
            </div>

            <!-- Botones -->
            <div class="col d-flex align-items-end gap-1">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                @if (request()->anyFilled(['buscar', 'universidad_id', 'nivel_academico', 'modalidad']))
                    <a href="{{ route('becas.index') }}" class="btn btn-outline-secondary"
                        title="Limpiar Filtros">✕</a>
                @endif
            </div>
        </form>
    </div>

    <div class="indexGrid">


        @foreach ($becas as $beca)
            <div class="tarjeta" id="tarjeta" data-url="{{ route('becas.show', $beca->id) }}">
                <div class="texto">
                    <p>{{ $beca->titulo }}</p>
                    <p id="resumen">{{ $beca->descripcion }}</p>
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


    <br>
    <a href="{{ route('becas.create') }}" class="btn-outline">Sugerir beca</a>
<div class="mt-4">
    {{ $becas->links('pagination::bootstrap-4') }}
</div>
    
    
</x-layout>
