<x-foro-layout>
    @push('estilo')
        @vite(['resources/css/foro/create.css'])
    @endpush

    <main class="foro-main">
        <div class="container form-main-container">

            <section class="form-container-card">
                <div class="thread-card form-card">
                    <div class="thread-header form-header-text">
                        <span class="thread-meta">{{ __('Comparte tus conocimientos') }}</span>
                        <h2 class="thread-title">{{ __('Crea un nuevo grupo') }}</h2>
                    </div>

                    <form action="{{ route('foro.store') }}" method="POST" enctype="multipart/form-data" class="oportunidad-form">
                        @csrf

                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="titulo">{{ __('Título') }}</label>
                                <input type="text" id="titulo" name="titulo" placeholder="{{ __('¿Cómo es la vida universitaria?') }}"
                                    value="{{ old('titulo') }}" class="@error('titulo') is-invalid @enderror" required>
                                @error('titulo')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="universidad_id">{{ __('Institución educativa') }}</label>
                                <select name="universidad_id" id="universidad_id" class="@error('universidad_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona una universidad') }}</option>
                                    @foreach ($universidades as $universidad)
                                        <option value="{{ $universidad->id }}"
                                            {{ old('universidad_id') == $universidad->id ? 'selected' : '' }}>
                                            {{ translate_db($universidad->nombre_completo) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('universidad_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="carrera_id">{{ __('Carrera') }}</label>
                                <select name="carrera_id" id="carrera_id" class="@error('carrera_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona una carrera') }}</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}"
                                            {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                            {{ translate_db($carrera->nombre) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('carrera_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="categoriaforo_id">{{ __('Categoría') }}</label>
                                <select name="categoriaforo_id" id="categoriaforo_id" class="@error('categoriaforo_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona una categoría') }}</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoriaforo_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ translate_db($categoria->categorias) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoriaforo_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="contenido">{{ __('Descripción') }}</label>
                                <textarea id="contenido" name="contenido" rows="4" required placeholder="{{ __('Escribe detalles del foro...') }}"
                                    class="@error('contenido') is-invalid @enderror">{{ old('contenido') }}</textarea>
                                @error('contenido')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('foro.index') }}" class="btn-cancel">← {{ __('Cancelar') }}</a>
                            <button type="submit" class="btn-submit"> {{ __('Crear') }}</button>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </main>
</x-foro-layout>