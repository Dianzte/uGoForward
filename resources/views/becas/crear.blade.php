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

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br><br>

        <label for="monto">Monto:</label>
        <input type="number" id="monto" name="monto" required><br><br>

        <button type="submit">Crear Beca</button>

    </form>

    <a href="{{ route('becas.index') }}">Volver a la lista de becas</a>
    
</body>
</html>