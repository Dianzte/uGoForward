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

    @include('navegacion.navbar')

    <main>

        <section>
            <div class="difuminado">
                {{ $slot }}
                
            </div>
            <div class="ocean">
                    <div class="ola">
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                            <g id="onda-base">
                                <path d="M0,60 Q150,0 300,60 T600,60 T900,60 T1200,60 L1200,120 L0,120 Z"
                                    class="onda"></path>
                            </g>

                            <use href="#onda-base" x="0" y="0" />

                            <use href="#onda-base" x="-600" y="0" />
                        </svg>
                    </div>
                    <div class="mar"></div>
                </div>
        </section>
    </main>

    <footer>
        El footer
    </footer>
</body>
@stack('script')

</html>
