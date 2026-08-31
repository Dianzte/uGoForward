<x-layout>
    <x-slot:titulo>
        {{ __('Crear Nueva Beca') }} - UGF
    </x-slot:titulo>
    <x-slot:angosto>
        angosto
    </x-slot:angosto>

    <div class="centrador">
        <span class="subtitulo">{{ __('Crear una oportunidad') }}</span>
        <h1 class="importante">{{ __('Crear Nueva Beca') }}</h1>

        <form action="{{ route('becas.store') }}" method="POST" enctype="multipart/form-data" class="form-beca">
            @csrf

            <div class="form-group full">
                <label for="titulo">{{ __('Título') }}:</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="{{ __('Título') }}" required>
                @error('titulo')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full">
                <label for="universidad_id">{{ __('Universidad') }}:</label>
                <select name="universidad_id" id="universidad_id" required>
                    <option value="">{{ __('Selecciona una institución') }}</option>
                    @foreach ($universidades as $universidad)
                        <option value="{{ $universidad->id }}"
                            {{ old('universidad_id') == $universidad->id ? 'selected' : '' }}>
                            {{ translate_db($universidad->nombre_completo) }}
                        </option>
                    @endforeach
                </select>
                @error('universidad_id')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="carrera_id">{{ __('Carrera') }}:</label>
                    <select name="carrera_id" id="carrera_id" required>
                        <option value="">{{ __('Selecciona una carrera') }}</option>
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->id }}"
                                {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                {{ translate_db($carrera->nombre) }}
                            </option>
                        @endforeach
                    </select>
                    @error('carrera_id')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="condicion_id">{{ __('Condiciones') }}:</label>
                    <select name="condicion_id" id="condicion_id" required>
                        <option value="">{{ __('Selecciona una condición') }}</option>
                        @foreach ($condiciones as $condicion)
                            <option value="{{ $condicion->id }}"
                                {{ old('condicion_id') == $condicion->id ? 'selected' : '' }}>
                                {{ translate_db($condicion->nombre) }}
                            </option>
                        @endforeach
                    </select>
                    @error('condicion_id')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ayuda_id">{{ __('Tipo de ayuda') }}:</label>
                    <select name="ayuda_id" id="ayuda_id" required>
                        <option value="">{{ __('Selecciona tipo de ayuda') }}</option>
                        @foreach ($ayuda as $itemAyuda)
                            <option value="{{ $itemAyuda->id }}" {{ old('ayuda_id') == $itemAyuda->id ? 'selected' : '' }}>
                                {{ translate_db($itemAyuda->nombre) }}
                            </option>
                        @endforeach
                    </select>
                    @error('ayuda_id')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="vencimiento">{{ __('Vencimiento') }}:</label>
                    <input type="date" id="vencimiento" name="vencimiento" value="{{ old('vencimiento') }}"
                        min="{{ date('Y-m-d') }}" required>
                    @error('vencimiento')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group full">
                <label for="descripcion">{{ __('Descripción') }}:</label>
                <textarea id="descripcion" name="descripcion" placeholder="{{ __('Escribe detalles de la convocatoria...') }}" required>{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full">
                <label for="url_oficial">{{ __('Enlace oficial') }}:</label>
                <input id="url_oficial" type="url" name="url_oficial" value="{{ old('url_oficial') }}" placeholder="https://..." pattern="https://.*">
                @error('url_oficial')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full">
                <label for="imagenes" class="btn-outline">{{ __('Imagen') }}:</label>
                <input type="file" id="imagenes" name="imagenes" accept="image/*" style="padding: 0.5rem; display:none;" >
                @error('imagen_id')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('becas.index') }}" class="btn-outline">← {{ __('Cancelar') }}</a>
                <button type="submit" class="btn-primary"> {{ __('Crear Beca') }}</button>
            </div>
        </form>
    </div>
</x-layout>
