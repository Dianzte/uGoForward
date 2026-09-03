<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Inicio de sesión') }} - UGF</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Raleway:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])
    @vite(['resources/css/Login.css', 'resources/css/temaUnido.css', 'resources/js/script.js'])

</head>

<body>
    @include('navegacion.navbar')

    <canvas id="ocean" class="ocean-scene"></canvas>
    <div id="hint" data-ocean-hint>{{ __('Haz clic en la luna para cambiar el día') }}</div>

    <div class="card">
        <div class="panel-left">
            <div class="left-top">
                <p class="eyebrow">{{ __('Gracias Por Visitarnos') }}</p>
                <h1>{{ __('Bienvenido') }}<br>{{ __('de') }} <em>{{ __('Nuevo a UGF') }}</em></h1>
                <div class="gold-bar"></div>
                <p>{{ __('Accede a tu cuenta y continúa donde lo dejaste. Tu experiencia te está esperando.') }}</p>
            </div>
            <div class="left-bottom">
                <div class="badge">
                    <span class="badge-dot"></span>
                    {{ __('Plataforma de preparación para becas segura & confiable') }}
                </div>
            </div>
            <div class="blob"></div>
        </div>

        <div class="panel-right">
            <div class="form-head">
                <h2>{{ __('Iniciar sesión') }}</h2>
                <p>{{ __('Ingresa tus credenciales para acceder a tu cuenta') }}</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                @if (session('status'))
                    <p
                        style="color:#2e7d32;background:#e8f5e9;border:1px solid #a5d6a7;padding:.5rem .8rem;border-radius:6px;font-size:.85rem;">
                        {{ session('status') }}</p>
                @endif

                <div class="field">
                    <label for="email">{{ __('Correo electrónico') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="{{ __('ejemplo@correo.com') }}" required autofocus>
                    @error('email')
                        <span class="error-message" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">{{ __('Contraseña') }}</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <span class="error-message" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot">{{ __('¿Olvidaste tu contraseña?') }}</a>
                @endif

                <button type="submit" class="btn-login">{{ __('LOGIN') }}</button>

                <div class="divider"><span>{{ __('o') }}</span></div>

                <p class="register-link">{{ __('¿No tienes cuenta?') }} <a href="{{ route('registro') }}">{{ __('Regístrate aquí') }}</a></p>
            </form>
        </div>
    </div>

</body>

</html>