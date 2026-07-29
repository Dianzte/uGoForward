<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro Estudiantil - UGF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@400;700&family=Nunito:wght@300;400;600;700&family=Spline+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    @vite(['resources/css/foro/index.css'])
    <link rel="stylesheet" href="foro.css">
</head>

<body>
    @include('navegacion.navbar')
    {{ $slot }}
    @include('navegacion.footer')

</body>

</html>
