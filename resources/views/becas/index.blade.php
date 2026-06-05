<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Becas disponibles</title>
    <link rel="stylesheet" href="{{ asset('css/becas.css') }}">
</head>

<body>

    <h1>Becas disponibles</h1>

    @foreach ($becas as $beca)
        <a href="{{ route('becas.show', $beca->id) }}">
            <div class="beca">
                <h2>{{ $beca->titulo }}</h2>
                <p>{{ $beca->descripcion }}</p>
            </div>
        </a>
    @endforeach

    <a href="{{ route('becas.crear') }}">Crear nueva beca</a>

</body>

</html>
