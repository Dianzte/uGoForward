<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Selecciona tu rol — UGF</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  @vite(['resources/css/rol-flow.css'])
</head>
<body class="rf-page">

  {{-- ============ OVERLAY DE INTRODUCCIÓN (3 pasos animados) ============ --}}
  <div class="rf-intro-overlay" id="rfIntroOverlay">
    <div class="rf-intro-panel">

      {{-- PASO 1 --}}
      <div class="rf-intro-step is-active" data-step="1">
        <p class="rf-intro-eyebrow">Bienvenido a UGF</p>
        <h2 class="rf-intro-title">Selecciona tu rol</h2>
        <p class="rf-intro-text">
          Antes de continuar, queremos mostrarte por qué esta elección importa
          para ti y para toda la comunidad UGF.
        </p>
        <div class="rf-intro-actions">
          <button type="button" class="rf-btn rf-btn-primary" data-rf-next>Continuar</button>
        </div>
      </div>

      {{-- PASO 2 --}}
      <div class="rf-intro-step" data-step="2">
        <p class="rf-intro-eyebrow">Dos roles, una misma meta</p>
        <h2 class="rf-intro-title">Ambos roles son fundamentales</h2>
        <p class="rf-intro-text">
          UGF conecta a quienes buscan crecer con quienes quieren impulsar ese crecimiento.
        </p>
        <div class="rf-roles-preview">
          <div class="rf-role-preview-item is-estudiante">
            <span class="rf-role-tag">Estudiante</span>
            <p>Representa el futuro, la superación y el talento que busca construir una carrera.</p>
          </div>
          <div class="rf-role-preview-item is-padrino">
            <span class="rf-role-tag">Padrino</span>
            <p>Representa el impulso, la oportunidad y la guía para hacer realidad las metas del estudiante.</p>
          </div>
        </div>
        <div class="rf-intro-actions">
          <button type="button" class="rf-btn rf-btn-ghost" data-rf-prev>Atrás</button>
          <button type="button" class="rf-btn rf-btn-primary" data-rf-next>Continuar</button>
        </div>
      </div>

      {{-- PASO 3 --}}
      <div class="rf-intro-step" data-step="3">
        <p class="rf-intro-eyebrow">Último paso</p>
        <h2 class="rf-intro-title">¿Estás listo para tomar este gran paso?</h2>
        <p class="rf-intro-text">
          Recuerda que una vez elijas tu rol, no podrás cambiarlo más adelante.
        </p>
        <div class="rf-intro-actions">
          <button type="button" class="rf-btn rf-btn-ghost" data-rf-prev>Atrás</button>
          <button type="button" class="rf-btn rf-btn-primary" id="rfIntroConfirm">Sí, continuar</button>
        </div>
      </div>

      <div class="rf-intro-progress" id="rfIntroProgress">
        <span class="is-active"></span><span></span><span></span>
      </div>
    </div>
  </div>

  {{-- ============ INTERFAZ PRINCIPAL DE SELECCIÓN ============ --}}
  <div class="rf-container">
    <div class="rf-card rf-select-card">
      <h2>¿Cómo quieres usar la plataforma?</h2>
      <p>Esta elección no podrás cambiarla después, así que tómate un momento antes de decidir.</p>

      @if ($errors->any())
        <div class="rf-alert rf-alert-error">
          <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="rf-select-options">
        <button type="button" class="rf-role-btn is-estudiante" onclick="abrirModalRol('estudiante')">
          <span class="rf-role-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c0 1.66 2.69 3 6 3s6-1.34 6-3v-5"/></svg>
          </span>
          <span>
            <strong>Soy Estudiante</strong>
            <span>Quiero orientación de carrera y explorar becas</span>
          </span>
          <svg class="rf-role-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>

        <button type="button" class="rf-role-btn is-padrino" onclick="abrirModalRol('padrino')">
          <span class="rf-role-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.35-9.5-8.5C.7 9 2 5.5 5.5 5c2-.28 3.5 1 4.5 2 1-1 2.5-2.28 4.5-2C18 5.5 19.3 9 17.5 12.5 15 16.65 12 21 12 21z"/></svg>
          </span>
          <span>
            <strong>Soy Padrino</strong>
            <span>Quiero apoyar financieramente a estudiantes</span>
          </span>
          <svg class="rf-role-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
      </div>
    </div>

    {{-- Formulario real, se envía solo al confirmar en el modal --}}
    <form id="rolForm" action="{{ route('rol.guardar') }}" method="POST">
      @csrf
      <input type="hidden" name="role" id="rolInput" value="">
    </form>
  </div>

  {{-- ============ MODAL DE CONFIRMACIÓN DE ROL ============ --}}
  <div class="rf-modal-overlay" id="rolModalOverlay">
    <div class="rf-modal" role="dialog" aria-modal="true" aria-labelledby="rolModalTitle">
      <h3 id="rolModalTitle"></h3>
      <p class="rf-modal-sub">Recuerda: no podrás cambiar esta elección más adelante.</p>
      <ul id="rolModalVentajas"></ul>
      <div class="rf-modal-actions">
        <button type="button" class="rf-btn rf-btn-ghost" onclick="cerrarModalRol()">Cancelar</button>
        <button type="button" class="rf-btn rf-btn-primary" onclick="confirmarRol()">Confirmar y continuar</button>
      </div>
    </div>
  </div>

<script>
  /* ================= INTRO OVERLAY (slide-down / pasos) ================= */
  (function () {
    const overlay  = document.getElementById('rfIntroOverlay');
    const steps    = Array.from(overlay.querySelectorAll('.rf-intro-step'));
    const dots     = Array.from(document.getElementById('rfIntroProgress').children);
    let current    = 1;

    function goTo(step) {
      steps.forEach(s => s.classList.toggle('is-active', Number(s.dataset.step) === step));
      dots.forEach((d, i) => d.classList.toggle('is-active', i === step - 1));
      current = step;
    }

    overlay.querySelectorAll('[data-rf-next]').forEach(btn => {
      btn.addEventListener('click', () => goTo(Math.min(current + 1, steps.length)));
    });
    overlay.querySelectorAll('[data-rf-prev]').forEach(btn => {
      btn.addEventListener('click', () => goTo(Math.max(current - 1, 1)));
    });

    document.getElementById('rfIntroConfirm').addEventListener('click', () => {
      overlay.classList.add('is-leaving');
      setTimeout(() => { overlay.style.display = 'none'; }, 480);
    });
  })();

  /* ================= MODAL DE CONFIRMACIÓN DE ROL ================= */
  const VENTAJAS = {
    estudiante: {
      titulo: 'Vas a registrarte como Estudiante',
      lista: [
        'Recibirás un test socioemocional para descubrir qué carrera universitaria se ajusta mejor a ti.',
        'Podrás explorar becas disponibles y guardarlas en tu perfil.',
        'Tu perfil quedará visible para padrinos que buscan apoyar estudiantes con tu área de interés.',
        'Acceso al foro para hacer preguntas a otros estudiantes y padrinos.',
      ],
    },
    padrino: {
      titulo: 'Vas a registrarte como Padrino',
      lista: [
        'Verás un tutorial paso a paso sobre cómo brindar ayuda financiera a estudiantes universitarios.',
        'Podrás revisar perfiles de estudiantes y sus áreas de interés.',
        'Tendrás visibilidad de las becas y programas de apoyo activos en la plataforma.',
        'Acceso al foro para conectar directamente con estudiantes y otras organizaciones.',
      ],
    },
  };

  let rolSeleccionado = null;
  const modalOverlay = document.getElementById('rolModalOverlay');
  const tituloEl = document.getElementById('rolModalTitle');
  const listaEl = document.getElementById('rolModalVentajas');
  const rolInput = document.getElementById('rolInput');
  const rolForm = document.getElementById('rolForm');

  function abrirModalRol(rol) {
    if (!VENTAJAS[rol]) return;
    rolSeleccionado = rol;
    tituloEl.textContent = VENTAJAS[rol].titulo;
    listaEl.innerHTML = '';
    VENTAJAS[rol].lista.forEach((texto) => {
      const li = document.createElement('li');
      li.innerHTML = `
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <span>${texto}</span>`;
      listaEl.appendChild(li);
    });
    modalOverlay.classList.add('is-open');
  }

  function cerrarModalRol() {
    modalOverlay.classList.remove('is-open');
    rolSeleccionado = null;
  }

  function confirmarRol() {
    if (!rolSeleccionado) return;
    rolInput.value = rolSeleccionado;
    rolForm.submit();
  }

  modalOverlay.addEventListener('click', (e) => { if (e.target === modalOverlay) cerrarModalRol(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModalRol(); });
</script>

</body>
</html>
