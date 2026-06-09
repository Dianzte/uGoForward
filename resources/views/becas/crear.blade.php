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
           @foreach($universidades as $universidad)
               <option value="{{ $universidad->id }}">{{ $universidad->nombre_completo }}</option>
           @endforeach
        </select>

        @error('universidad_id')
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
        
        <button type="submit">Crear Beca</button>

    </form>

    <a href="{{ route('becas.index') }}">Volver a la lista de becas</a>
    
</body>
</html>