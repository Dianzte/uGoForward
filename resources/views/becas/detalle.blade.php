<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>
</head>

<body>

    <h1>Buenos días</h1>
    <h2> Beca {{ $beca->titulo }}</h2>
    <p>{{ $beca->descripcion }}</p> 

    <a href="{{ route('becas.index') }}">Volver</a>

</body>

</html>
