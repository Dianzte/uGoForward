<x-layout>

    
    <x-slot:titulo>
        Crear Nueva Beca
    </x-slot:titulo>
    <x-slot:angosto>
        angosto
    </x-slot:angosto>

    <div class="centrador">

        <h1 class="importante">Crear una oportunidad</h1>

        <form action="{{ route('becas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grupoCampo completo">
                <div class="inputInterior">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required><br><br>
                    @error('titulo')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grupoCampo completo">
                <div class="inputInterior">
                    <label for="universidad_id">Universidad</label>
                    <select name="universidad_id" id="universidad_id" required>
                        <option value="">Selecciona una institución</option>
                        @foreach ($universidades as $universidad)
                            <option value="{{ $universidad->id }}"
                                {{ old('universidad_id') == $universidad->id ? 'selected' : '' }}>
                                {{ $universidad->nombre_completo }}</option>
                        @endforeach
                    </select>

                    @error('universidad_id')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grupoCampo">

                <div class="inputInterior">
                    <label for="carrera_id">Carrera</label>
                    <select name="carrera_id" id="carrera_id" required>
                        <option value="">Selecciona una carrera</option>
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->id }}"
                                {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                {{ $carrera->nombre }}</option>
                        @endforeach
                    </select>

                    @error('carrera_id')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
                <div class="inputInterior">
                    <label for="condicion_id">Condiciones</label>
                    <select name="condicion_id" id="condicion_id" required>
                        <option value="">Selecciona una condición</option>
                        @foreach ($condiciones as $condicion)
                            <option value="{{ $condicion->id }}"
                                {{ old('condicion_id') == $condicion->id ? 'selected' : '' }}>
                                {{ $condicion->nombre }}</option>
                        @endforeach
                    </select>

                    @error('condicion_id')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grupoCampo">

                <div class="inputInterior">
                    <label for="ayuda_id">Tipo de ayuda</label>
                    <select name="ayuda_id" id="ayuda_id" required>
                        <option value="">Selecciona tipo de ayuda</option>
                        @foreach ($ayuda as $ayuda)
                            <option value="{{ $ayuda->id }}" {{ old('ayuda_id') == $ayuda->id ? 'selected' : '' }}>
                                {{ $ayuda->nombre }}</option>
                        @endforeach
                    </select>

                    @error('ayuda_id')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
                <div class="inputInterior">
                    <label for="vencimiento">Vencimiento:</label>
                    <input type="date" id="vencimiento" name="vencimiento" value="{{ old('vencimiento') }}"
                        min="{{ date('Y-m-d') }}" required>
                    @error('vencimiento')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="grupoCampo">
                <div class="inputInterior completo">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required></textarea><br><br>
                    @error('descripcion')
                        <div> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grupoCampo">
                <label for="imagenes" class="btn-outline">Imagen </label>
                <input type="file" id="imagenes" name="imagenes" accept="image/*">


                @error('imagen_id')
                    <div> {{ $message }}</div>
                @enderror
                <button type="submit" class="btn-outline amarillo">Crear Beca</button>
            </div>



        </form>

       

        <a href="{{ route('becas.index') }}" class="btn-outline ">Volver a la lista de becas</a>
    </div>


   


</x-layout>
