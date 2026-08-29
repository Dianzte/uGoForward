<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'U Go Forward' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/css/becasListado.css'])
</head>

<body>

    @include('navegacion.navbar')

    <main>

        <section class="section-becas">
            <div class="difuminado {{ $angosto ?? '' }}">
                {{ $slot }}

            </div>
            <div class="ocean">
                <div class="ola">
                    <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <g id="onda-base">
                            <path d="M0,60 Q150,0 300,60 T600,60 T900,60 T1200,60 L1200,120 L0,120 Z" class="onda">
                            </path>
                        </g>

                        <use href="#onda-base" x="0" y="0" />

                        <use href="#onda-base" x="-600" y="0" />
                    </svg>
                </div>
                <div class="mar"></div>
            </div>
        </section>
    </main>

    @include('navegacion.footer')
</body>
@vite(['resources/js/becasListado.js'])

</html>
