<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'U Go Forward' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@props(['background'])

<body class="{{ $background ?? 'nocargalavariable' }}">

    <nav>
        Barra de navegación
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer>
        El footer
    </footer>
</body>

</html>
