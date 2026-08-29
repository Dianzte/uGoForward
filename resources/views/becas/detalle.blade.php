<x-layout>
    <x-slot:titulo>
        {{ __('Información de la beca') }} - {{ translate_db($beca->titulo) }}
    </x-slot:titulo>
    <x-slot:angosto>
        angosto
    </x-slot:angosto>

    <div class="detalle-container">
        <div class="detalle-header">
            <span class="subtitulo">{{ __('Información de la beca') }}</span>
            <h1 class="detalle-title">{{ translate_db($beca->titulo) }}</h1>
            <div class="detalle-univ-badge">
                 {{ translate_db($beca->universidad->nombre_completo ?? 'El Salvador') }}
            </div>
        </div>

        <div class="detalle-section">
            <h3> {{ __('Descripción') }}</h3>
            <p>{{ translate_db($beca->descripcion) }}</p>
        </div>

        <!-- Specifications Grid -->
        <div class="specs-grid">
            @if (isset($beca->nivel_academico))
                <div class="spec-item">
                    <span class="spec-label">{{ __('Nivel académico') }}</span>
                    <span class="spec-value">{{ translate_db($beca->nivel_academico) }}</span>
                </div>
            @endif

            @if (isset($beca->modalidad))
                <div class="spec-item">
                    <span class="spec-label">{{ __('Modalidad') }}</span>
                    <span class="spec-value">{{ translate_db($beca->modalidad) }}</span>
                </div>
            @endif

            @if (isset($beca->vencimiento))
                <div class="spec-item">
                    <span class="spec-label">{{ __('Vencimiento') }}</span>
                    <span class="spec-value" style="color: var(--gold, #e8c847);">
                        {{ is_string($beca->vencimiento) ? $beca->vencimiento : $beca->vencimiento->format('d/m/Y') }}
                    </span>
                </div>
            @endif

            @if (isset($beca->cum_promedio_minimo))
                <div class="spec-item">
                    <span class="spec-label">{{ __('CUM promedio') }}</span>
                    <span class="spec-value">{{ $beca->cum_promedio_minimo }}</span>
                </div>
            @endif
        </div>

        @if (isset($beca->carrera->nombre))
            <div class="detalle-section">
                <h3>{{ __('Carrera') }}</h3>
                <p>{{ translate_db($beca->carrera->nombre) }}</p>
            </div>
        @elseif (!empty($beca->carreras_cobertura))
            <div class="detalle-section">
                <h3> {{ __('Carrera') }}</h3>
                <ul>
                    @foreach (translate_array($beca->carreras_cobertura) as $carrera)
                        <li>{{ $carrera }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($beca->condicion->nombre))
            <div class="detalle-section">
                <h3> {{ __('Condiciones') }}</h3>
                <p>{{ translate_db($beca->condicion->nombre) }}</p>
            </div>
        @endif

        @if (isset($beca->ayuda->nombre))
            <div class="detalle-section">
                <h3> {{ __('Ayuda') }}</h3>
                <p>{{ translate_db($beca->ayuda->nombre) }}</p>
            </div>
        @endif

        @if (!empty($beca->cobertura_resumen))
            <div class="detalle-section">
                <h3> {{ __('Cobertura') }}</h3>
                <p>{{ translate_db($beca->cobertura_resumen) }}</p>
            </div>
        @endif

        @if (!empty($beca->requisitos))
            <div class="detalle-section">
                <h3> {{ __('Requisitos') }}</h3>
                <ul>
                    @foreach (translate_array($beca->requisitos) as $requisito)
                        <li>{{ $requisito }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($beca->imagen->ruta))
            <div class="detalle-section" style="text-align: center;">
                <img src="{{ asset('storage/' . $beca->imagen->ruta) }}" alt="{{ translate_db($beca->titulo) }}"
                    style="max-width: 100%; max-height: 350px; border-radius: 12px; object-fit: cover;">
            </div>
        @endif

        <div class="detalle-actions">
            @if (!empty($beca->url_oficial))
                <a href="{{ $beca->url_oficial }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                     {{ __('Visitar sitio oficial') }} →
                </a>
            @endif
            <a href="{{ route('becas.index') }}" class="btn-outline">
                ← {{ __('Volver a la lista de becas') }}
            </a>
        </div>
    </div>
</x-layout>
