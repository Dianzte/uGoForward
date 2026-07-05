<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'U Go Forward' }}</title>
    @vite(['resources/css/app.css'])
    @stack('estilo')
</head>

@props(['background'])
@props(['seccion'])

<body class="fondo">

    <nav>
        Barra de navegación
    </nav>
    <h1>{{$encabezado ?? ''}}</h1>

    <main>
        {{ $slot }}
    </main>

    <footer>
        El footer
    </footer>
</body>
@stack('script')

</html>
