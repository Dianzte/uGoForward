<x-layout>
    <x-slot:titulo>
        {{ __('Becas disponibles') }} - UGF
    </x-slot:titulo>

    <span class="subtitulo">{{ __('Un mar de oportunidades') }}</span>
    <h1 class="importante">{{ __('Explora las becas disponibles') }}</h1>

    <div class="filtros-container">
        <form action="{{ route('becas.filtrar') }}" method="GET" class="filtros">

            <!-- Búsqueda por Texto -->
            <div class="col">
                <label>{{ __('Buscar por palabra clave') }}</label>
                <input type="text" name="buscar" placeholder="{{ __('Ej: Excelencia, Cómputo...') }}"
                    value="{{ request('buscar') }}">
            </div>

            <!-- Filtro por Universidad -->
            <div class="col">
                <label>{{ __('Universidad') }}</label>
                <select name="universidad_id">
                    <option value="">{{ __('Todas las Universidades') }}</option>
                    @foreach ($universidades as $uni)
                        <option value="{{ $uni->id }}"
                            {{ request('universidad_id') == $uni->id ? 'selected' : '' }}>
                            {{ translate_db($uni->nombre_completo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Nivel Académico -->
            <div class="col">
                <label>{{ __('Nivel') }}</label>
                <select name="nivel_academico">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="Técnico" {{ request('nivel_academico') == 'Técnico' ? 'selected' : '' }}>{{ __('Técnico') }}</option>
                    <option value="Licenciatura" {{ request('nivel_academico') == 'Licenciatura' ? 'selected' : '' }}>{{ __('Licenciatura') }}</option>
                    <option value="Ingeniería" {{ request('nivel_academico') == 'Ingeniería' ? 'selected' : '' }}>{{ __('Ingeniería') }}</option>
                    <option value="Maestría" {{ request('nivel_academico') == 'Maestría' ? 'selected' : '' }}>{{ __('Maestría') }}</option>
                </select>
            </div>

            <!-- Filtro por Modalidad -->
            <div class="col">
                <label>{{ __('Modalidad') }}</label>
                <select name="modalidad">
                    <option value="">{{ __('Todas') }}</option>
                    <option value="Presencial" {{ request('modalidad') == 'Presencial' ? 'selected' : '' }}>{{ __('Presencial') }}</option>
                    <option value="Virtual" {{ request('modalidad') == 'Virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
                    <option value="Híbrida" {{ request('modalidad') == 'Híbrida' ? 'selected' : '' }}>{{ __('Híbrida') }}</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="filtros-actions">
                <button type="submit" class="btn-primary" style="padding: 0.65rem 1.4rem;">{{ __('Filtrar') }}</button>
                @if (request()->anyFilled(['buscar', 'universidad_id', 'nivel_academico', 'modalidad']))
                    <a href="{{ route('becas.index') }}" class="btn-filter-clear" title="{{ __('Limpiar Filtros') }}">✕</a>
                @endif
            </div>
        </form>
    </div>

    <div class="indexGrid">
        @forelse ($becas as $beca)
            <a href="{{ route('becas.show', $beca->id) }}" class="tarjeta">
                <div>
                    <div class="tarjeta-header">
                        <span class="tarjeta-badge">{{ translate_db($beca->nivel_academico ?? __('Becas Nacionales')) }}</span>
                        @if($beca->vencimiento)
                            <span style="font-size: 0.75rem; color: var(--gold, #e8c847); font-weight: 600;">
                                 {{ is_string($beca->vencimiento) ? $beca->vencimiento : $beca->vencimiento->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                    <h3 class="tarjeta-titulo">{{ translate_db($beca->titulo) }}</h3>
                    <p class="tarjeta-desc">{{ translate_db($beca->descripcion) }}</p>
                </div>
                <div class="tarjeta-footer">
                    <span class="tarjeta-univ"> {{ translate_db($beca->universidad->nombre_completo ?? 'El Salvador') }}</span>
                    <span class="tarjeta-btn">{{ __('Ver Fecha →') }}</span>
                </div>
            </a>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: var(--text-2);">
                <p style="font-size: 1.1rem; margin-bottom: 12px;">{{ __('No se encontraron becas con los filtros seleccionados.') }}</p>
                <a href="{{ route('becas.index') }}" class="btn-outline">{{ __('Limpiar Filtros') }}</a>
            </div>
        @endforelse
    </div>

    @if (Auth::user() && Auth::user()->rol == 'padrino')
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 30px;">
        <a href="{{ route('becas.create') }}" class="btn-outline"> {{ __('Sugerir beca') }}</a>
    </div>
    @endif

    <div class="mt-4">
        {{ $becas->links('pagination::bootstrap-4') }}
    </div>
</x-layout>
