<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - UGF</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @vite(['resources/css/Register.css', 'resources/js/script.js'])
</head>
<body>
  @include('navegacion.navbar')

<canvas id="ocean"></canvas>
<div id="hint">Haz clic en la luna para cambiar el día</div>

<div class="scene">
  <div class="card-flipper" id="flipper">

    <div class="face face-front">
      <div class="panel-left">
        <div class="left-top">
          <p class="eyebrow">Bienvenido a UGF</p>
          <h1>Gracias por<br>visitar<br><em>UGF</em></h1>
          <div class="gold-bar"></div>
          <p>Crea tu cuenta y accede a crear tu propia historia de éxito académico.</p>
        </div>
        <div class="left-bottom">
          <div class="badge">
            <span class="badge-dot"></span>
            Plataforma de preparación para becas
          </div>
        </div>
        <div class="blob"></div>
      </div>

      <div class="panel-right">
        <div class="form-head">
          <h2>Registro</h2>
          <p>¿Cómo deseas participar en UGF?</p>
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
            <h3>Registrarse</h3>
            <p>Sumergete en esta experiencia, donde la esperanza y esfuerzo sobran</p>
          </div>
        </div>

        <div class="arrow-hint">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          Selecciona una opción para continuar
        </div>

        <p class="login-link">¿Ya tienes cuenta?<a href="{{ route('login') }}">Inicia sesión aquí</a></p>
      </div>
    </div>

    <div class="face face-back-right">
      <div class="panel-left">
        <div class="left-top">
          <p class="eyebrow">Registro Usuario</p>
          <h1>Sé parte<br>del<br><em>cambio</em></h1>
          <div class="gold-bar"></div>
          <p>Tu apoyo transforma vidas. Conecta con estudiantes con talento y abre puertas a su futuro.</p>
        </div>
        <div class="left-bottom">
          <div class="badge">
            <span class="badge-dot"></span>
            Apoya el talento estudiantil
          </div>
        </div>
        <div class="blob"></div>
      </div>

      <div class="panel-form">
        <button type="button" class="btn-back" onclick="ocultarFormulario()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
          Volver
        </button>

        <div class="tipo-badge">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#d4a017" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          Registrar cuenta
        </div>

        <h2>Crear cuenta</h2>
        <p>Completa el formulario para registrarte</p>

       
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
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Ingresar usuario" value="{{ old('usuario') }}" required>
            @error('usuario')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Nombre completo</label>
            <input type="text" name="nombre" placeholder="Ingrese su nombre" value="{{ old('nombre') }}" required>
            @error('nombre')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Correo electrónico</label>
            <input type="email" name="correo" placeholder="Ingrese su correo electrónico" value="{{ old('correo') }}" required>
            @error('correo')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Contraseña</label>
            <input type="password" name="contrasena" placeholder="Registrar su contraseña" required>
            @error('contrasena')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Fecha de nacimiento</label>
            <input type="date" id="fechaNac" name="fechaNac" value="{{ old('fechaNac') }}" required>
            @error('fechaNac')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Departamento</label>
            <select name="departamento" required class="input-select">
              <option value="">Seleccione su departamento</option>
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
            {{-- Se autocompleta mediante JavaScript con el evento change/DOMContentLoaded --}}
          </div>

          <button type="submit" class="btn-login">Registrarse</button>
        </form>

        <div class="divider"><span>o</span></div>
        <p class="register-link">¿Ya tienes cuenta?<a href="{{ route('login') }}">Inicia sesión</a></p>
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

  // Función corregida para calcular la edad exacta sin problemas de zona horaria
  function evaluarEdad() {
      let inputFecha = document.getElementById("fechaNac").value;
      if (!inputFecha) return;

      // Dividimos el string "YYYY-MM-DD" directamente para evitar fallos de zona horaria UTC
      let partes = inputFecha.split('-');
      let anoNac = parseInt(partes[0], 10);
      let mesNac = parseInt(partes[1], 10) - 1; // Los meses en JS van de 0 a 11
      let diaNac = parseInt(partes[2], 10);

      let fechaNacimiento = new Date(anoNac, mesNac, diaNac);
      let hoy = new Date();

      let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
      let mesDiferencia = hoy.getMonth() - fechaNacimiento.getMonth();

      // Ajuste si aún no ha cumplido años en el año actual
      if (mesDiferencia < 0 || (mesDiferencia === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
          edad--;
      }

      let campo = document.getElementById("campoDocumento");

      if (edad < 16) {
          campo.innerHTML = "<p style='color:red; font-size:.85rem; font-weight:600; margin-top:.3rem;'>Debes tener al menos 16 años para registrarte.</p>";
      }
      else if (edad >= 16 && edad < 18) {
          let oldNie = "{{ old('nie') }}";
          campo.innerHTML = `
            <label>NIE</label>
            <input type="text" name="nie" placeholder="Ingrese su NIE" value="${oldNie}" required>
          `;
      }
      else {
          let oldDui = "{{ old('dui') }}";
          campo.innerHTML = `
            <label>DUI</label>
            <input type="text" name="dui" placeholder="Ingrese su DUI" value="${oldDui}" required>
          `;
      }
  }

  // Escuchar el evento de cambio de fecha
  document.getElementById("fechaNac").addEventListener("change", evaluarEdad);

  // Evaluar automáticamente si la página se recarga o si viene un valor previo
  document.addEventListener('DOMContentLoaded', function() {
      evaluarEdad();
  });
</script>

</body>
</html>