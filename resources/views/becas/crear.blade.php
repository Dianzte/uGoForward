<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Beca</title>
</head>

<body>

    <h1>Crear Nueva Beca</h1>
    <form action="{{ route('becas.store') }}" method="POST">
        @csrf
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>
        @error('titulo')
            <div> {{ $message }}</div>
        @enderror

        <select name="universidad_id" id="universidad_id" required>
            <option value="">Selecciona una institución</option>
            @foreach ($universidades as $universidad)
                <option value="{{ $universidad->id }}">{{ $universidad->nombre_completo }}</option>
            @endforeach
        </select>

        @error('universidad_id')
            <div> {{ $message }}</div>
        @enderror

        <select name="carrera_id" id="carrera_id" required>
            <option value="">Selecciona una carrera</option>
            @foreach ($carreras as $carrera)
                <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
            @endforeach
        </select>

        @error('carrera_id')
            <div> {{ $message }}</div>
        @enderror

        <select name="ayuda_id" id="ayuda_id" required>
            <option value="">Selecciona tipo de ayuda</option>
            @foreach ($ayuda as $ayuda)
                <option value="{{ $ayuda->id }}">{{ $ayuda->nombre }}</option>
            @endforeach
        </select>

        @error('ayuda_id')
            <div> {{ $message }}</div>
        @enderror

        <select name="condicion_id" id="condicion_id" required>
            <option value="">Selecciona una condición</option>
            @foreach ($condiciones as $condicion)
                <option value="{{ $condicion->id }}">{{ $condicion->nombre }}</option>
            @endforeach
        </select>

        @error('condicion_id')
            <div> {{ $message }}</div>
        @enderror

        <label for="duracion">Duración:</label>
        <input type="date" id="duracion" name="duracion" required>
        @error('duracion')
            <div> {{ $message }}</div>
        @enderror



        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br><br>
        @error('descripcion')
            <div> {{ $message }}</div>
        @enderror
        <label for="monto">Monto:</label>
        <input type="number" id="monto" name="monto" required><br><br>
        @error('monto')
            <div> {{ $message }}</div>
        @enderror

        <select name="imagen_id" id="imagen_id" required>
            <option value="">Selecciona una imagen</option>
            @foreach ($imagenes as $imagen)
                <option value="{{ $imagen->id }}">{{ $imagen->ruta }}</option>
            @endforeach
        </select>

        @error('imagen_id')
            <div> {{ $message }}</div>
        @enderror

        <button type="submit">Crear Beca</button>

    </form>

    <a href="{{ route('becas.index') }}">Volver a la lista de becas</a>

</body>

</html>
