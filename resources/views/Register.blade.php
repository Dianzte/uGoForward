<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Registro') }} - UGF</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @vite(['resources/css/Register.css', 'resources/js/script.js'])
</head>
<body>
  @include('navegacion.navbar')

<canvas id="ocean"></canvas>
<div id="hint">{{ __('Haz clic en la luna para cambiar el día') }}</div>

<div class="scene">
  <div class="card-flipper" id="flipper">

    <div class="face face-front">
      <div class="panel-left">
        <div class="left-top">
          <p class="eyebrow">{{ __('Bienvenido a UGF') }}</p>
          <h1>{{ __('Gracias por') }}<br>{{ __('visitar') }}<br><em>UGF</em></h1>
          <div class="gold-bar"></div>
          <p>{{ __('Crea tu cuenta y accede a crear tu propia historia de éxito académico.') }}</p>
        </div>
        <div class="left-bottom">
          <div class="badge">
            <span class="badge-dot"></span>
            {{ __('Plataforma de preparación para becas') }}
          </div>
        </div>
        <div class="blob"></div>
      </div>

      <div class="panel-right">
        <div class="form-head">
          <h2>{{ __('Registro') }}</h2>
          <p>{{ __('¿Cómo deseas participar en UGF?') }}</p>
        </div>

        <div class="choice-grid">
          <div class="choice-card" onclick="mostrarFormulario()">
            <div class="choice-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="#d4a017" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <h3>{{ __('Registrarse') }}</h3>
            <p>{{ __('Sumergete en esta experiencia, donde la esperanza y esfuerzo sobran') }}</p>
          </div>
        </div>

        <div class="arrow-hint">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          {{ __('Selecciona una opción para continuar') }}
        </div>

        <p class="login-link">{{ __('¿Ya tienes cuenta?') }}<a href="{{ route('login') }}">{{ __('Inicia sesión aquí') }}</a></p>
      </div>
    </div>

    <div class="face face-back-right">
      <div class="panel-left">
        <div class="left-top">
          <p class="eyebrow">{{ __('Registro Usuario') }}</p>
          <h1>{{ __('Sé parte') }}<br>{{ __('del') }}<br><em>{{ __('cambio') }}</em></h1>
          <div class="gold-bar"></div>
          <p>{{ __('Tu apoyo transforma vidas. Conecta con estudiantes con talento y abre puertas a su futuro.') }}</p>
        </div>
        <div class="left-bottom">
          <div class="badge">
            <span class="badge-dot"></span>
            {{ __('Apoya el talento estudiantil') }}
          </div>
        </div>
        <div class="blob"></div>
      </div>

      <div class="panel-form">
        <button type="button" class="btn-back" onclick="ocultarFormulario()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
          {{ __('Volver') }}
        </button>

        <div class="tipo-badge">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#d4a017" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          {{ __('Registrar cuenta') }}
        </div>

        <h2>{{ __('Crear cuenta') }}</h2>
        <p>{{ __('Completa el formulario para registrarte') }}</p>

        <form id="formRegistro" action="{{ route('registro.store') }}" method="POST">
          @csrf

          @if ($errors->any())
            <div style="background:#ffe5e5;border:1px solid #ff9b9b;color:#a30000;padding:.6rem .8rem;border-radius:6px;font-size:.85rem;margin-bottom:1rem;">
              <ul style="margin:0;padding-left:1.1rem;">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="field">
            <label>{{ __('Usuario') }}</label>
            <input type="text" name="usuario" placeholder="{{ __('Ingresar usuario') }}" value="{{ old('usuario') }}" required>
            @error('usuario')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>{{ __('Nombre completo') }}</label>
            <input type="text" name="nombre" placeholder="{{ __('Ingrese su nombre') }}" value="{{ old('nombre') }}" required>
            @error('nombre')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>{{ __('Correo electrónico') }}</label>
            <input type="email" name="correo" placeholder="{{ __('Ingrese su correo electrónico') }}" value="{{ old('correo') }}" required>
            @error('correo')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>{{ __('Contraseña') }}</label>
            <input type="password" name="contrasena" placeholder="{{ __('Registrar su contraseña') }}" required>
            @error('contrasena')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>{{ __('Fecha de nacimiento') }}</label>
            <input type="date" id="fechaNac" name="fechaNac" value="{{ old('fechaNac') }}" required>
            @error('fechaNac')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>{{ __('Departamento') }}</label>
            <select name="departamento" required class="input-select">
              <option value="">{{ __('Seleccione su departamento') }}</option>
              @php
                $departamentos = ['Ahuachapán', 'Santa Ana', 'Sonsonate', 'Chalatenango', 'La Libertad', 'San Salvador', 'Cuscatlán', 'La Paz', 'Cabañas', 'San Vicente', 'Usulután', 'San Miguel', 'Morazán', 'La Unión'];
              @endphp
              @foreach($departamentos as $depto)
                <option value="{{ $depto }}" {{ old('departamento') == $depto ? 'selected' : '' }}>{{ $depto }}</option>
              @endforeach
            </select>
            @error('departamento')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field" id="campoDocumento">
          </div>

          <button type="submit" class="btn-login">{{ __('Registrarse') }}</button>
        </form>

        <div class="divider"><span>{{ __('o') }}</span></div>
        <p class="register-link">{{ __('¿Ya tienes cuenta?') }}<a href="{{ route('login') }}">{{ __('Inicia sesión') }}</a></p>
      </div>
    </div>

  </div>
</div>

<script>
  function mostrarFormulario() {
    document.getElementById('flipper').classList.add('is-flipped');
  }
  function ocultarFormulario() {
    document.getElementById('flipper').classList.remove('is-flipped');
  }

  @if ($errors->any())
    document.addEventListener('DOMContentLoaded', mostrarFormulario);
  @endif

  function evaluarEdad() {
      let inputFecha = document.getElementById("fechaNac").value;
      if (!inputFecha) return;

      let partes = inputFecha.split('-');
      let anoNac = parseInt(partes[0], 10);
      let mesNac = parseInt(partes[1], 10) - 1;
      let diaNac = parseInt(partes[2], 10);

      let fechaNacimiento = new Date(anoNac, mesNac, diaNac);
      let hoy = new Date();

      let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
      let mesDiferencia = hoy.getMonth() - fechaNacimiento.getMonth();

      if (mesDiferencia < 0 || (mesDiferencia === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
          edad--;
      }

      let campo = document.getElementById("campoDocumento");

      if (edad < 16) {
          campo.innerHTML = "<p style='color:red; font-size:.85rem; font-weight:600; margin-top:.3rem;'>{{ __('Debes tener al menos 16 años para registrarte.') }}</p>";
      }
      else {
          let oldNie = "{{ old('nie') }}";
          campo.innerHTML = `
            <label>{{ __('NIE') }}</label>
            <input type="text" name="nie" placeholder="{{ __('Ingrese su NIE') }}" value="${oldNie}" required>
          `;
      }
  }

  document.getElementById("fechaNac").addEventListener("change", evaluarEdad);

  document.addEventListener('DOMContentLoaded', function() {
      evaluarEdad();
  });
</script>

</body>
</html>