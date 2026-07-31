<x-foro-layout>
@push('estilo')
    @vite(['resources/css/foro/create.css'])
    @endpush
        <main class="foro-main">
        <div class="container form-main-container">

            <!-- Centered Form Card (Rectangle 26 theme scaled) -->
            <section class="form-container-card">
                <div class="thread-card form-card">
                    <div class="thread-header form-header-text">
                        <span class="thread-meta">Comparte tus conocimientos</span>
                        <h2 class="thread-title">Crea un nuevo grupo</h2>
                    </div>

                    <!-- Form submission -->
                    <form action="{{ route('foro.store') }}" method="POST" enctype="multipart/form-data" class="oportunidad-form">
                        @csrf

                        <!-- Form Grid Layout -->
                        <div class="form-grid">

                            <div class="form-group full-width">
                                <label for="titulo">Título</label>
                                <input type="text" id="titulo" name="titulo" placeholder="¿Cómo es la vida universitaria?"
                                    value="{{ old('titulo') }}" class="@error('titulo') is-invalid @enderror">
                                @error('titulo')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="universidad_id">Institución educativa</label>
                                <select name="universidad_id" id="universidad_id"
                                    class="@error('universidad_id') is-invalid @enderror">
                                    <option value="">Selecciona una universidad</option>
                                    @foreach ($universidades as $universidad)
                                        <option value="{{ $universidad->id }}"
                                            {{ old('universidad_id') == $universidad->id ? 'selected' : '' }}>
                                            {{ $universidad->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('universidad_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="carrera_id">Carrera</label>
                                <select name="carrera_id" id="carrera_id" class="@error('carrera_id') is-invalid @enderror">
                                    <option value="">Selecciona una carrera</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}"
                                            {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('carrera_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="categoriaforo_id">Categoría</label>
                                <select name="categoriaforo_id" id="categoriaforo_id"
                                    class="@error('categoriaforo_id') is-invalid @enderror">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoriaforo_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->categorias }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoriaforo_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="contenido">Descripción</label>
                                <textarea id="contenido" name="contenido" rows="4" required placeholder="Escribe detalles del foro..."
                                    class="@error('contenido') is-invalid @enderror">{{ old('contenido') }}</textarea>
                                @error('contenido')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('foro.index') }}" class="btn-cancel">Cancelar</a>
                            <button type="submit" class="btn-submit">Crear</button>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </main>

</x-foro-layout>