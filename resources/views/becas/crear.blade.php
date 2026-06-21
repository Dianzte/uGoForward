<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Beca</title>
</head>

<body>

    <h1>Crear Nueva Beca</h1>
    <form action="{{ route('becas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required><br><br>
        @error('titulo')
            <div> {{ $message }}</div>
        @enderror

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

        <select name="carrera_id" id="carrera_id" required>
            <option value="">Selecciona una carrera</option>
            @foreach ($carreras as $carrera)
                <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                    {{ $carrera->nombre }}</option>
            @endforeach
        </select>

        @error('carrera_id')
            <div> {{ $message }}</div>
        @enderror

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

        <select name="condicion_id" id="condicion_id" required>
            <option value="">Selecciona una condición</option>
            @foreach ($condiciones as $condicion)
                <option value="{{ $condicion->id }}" {{ old('condicion_id') == $condicion->id ? 'selected' : '' }}>
                    {{ $condicion->nombre }}</option>
            @endforeach
        </select>

        @error('condicion_id')
            <div> {{ $message }}</div>
        @enderror

        <label for="vencimiento">Vencimiento:</label>
        <input type="date" id="vencimiento" name="vencimiento" value="{{ old('vencimiento') }}"
            min="{{ date('Y-m-d') }}" required>
        @error('vencimiento')
            <div> {{ $message }}</div>
        @enderror



        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required></textarea><br><br>
        @error('descripcion')
            <div> {{ $message }}</div>
        @enderror

        <label for="imagenes">Imagen </label>
        <input type="file" id="imagenes" name="imagenes" accept="image/*" >


        @error('imagen_id')
            <div> {{ $message }}</div>
        @enderror

        <button type="submit">Crear Beca</button>

        
    </form>
    <button onclick="testValores()">valores de prueba</button>

    <a href="{{ route('becas.index') }}">Volver a la lista de becas</a>

</body>

<script>
    let numeroTest = 1;

    function testValores() {
        let fechaAleatoria = intervalo(2028, 2030) + "-" + 0  + intervalo(1, 9) + "-"  + intervalo(1, 2)+ intervalo(1, 7)

        document.getElementById("titulo").value = "Titulo de prueba" + numeroTest

        document.getElementById("universidad_id").value = intervalo(1, 6)
        document.getElementById("carrera_id").value = intervalo(1, 5)
        document.getElementById("ayuda_id").value = intervalo(1, 5)
        document.getElementById("condicion_id").value = intervalo(1, 5)
        document.getElementById("vencimiento").value =  fechaAleatoria
        document.getElementById("descripcion").value = "descripción de prueba número" + numeroTest

        numeroTest++


    }

    function intervalo(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
</script>

</html>
