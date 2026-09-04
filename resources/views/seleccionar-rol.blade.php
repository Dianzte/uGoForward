<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('Select your role') }} — UGF</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  @vite(['resources/css/rol-flow.css', 'resources/css/temaUnido.css', 'resources/js/script.js'])
</head>
<body class="rf-page">

<canvas class="ocean-scene"></canvas>


  {{-- ============ OVERLAY DE INTRODUCCIÓN (3 pasos animados) ============ --}}
  <div class="rf-intro-overlay" id="rfIntroOverlay">
    <div class="rf-intro-panel">

      {{-- PASO 1 --}}
      <div class="rf-intro-step is-active" data-step="1">
        <p class="rf-intro-eyebrow">{{ __('Welcome to UGF') }}</p>
        <h2 class="rf-intro-title">{{ __('Select your role') }}</h2>
        <p class="rf-intro-text">
          {{ __('Before continuing, we want to show you why this choice matters for you and the entire UGF community.') }}
        </p>
        <div class="rf-intro-actions">
          <button type="button" class="rf-btn rf-btn-primary" data-rf-next>{{ __('Continue') }}</button>
        </div>
      </div>

      {{-- PASO 2 --}}
      <div class="rf-intro-step" data-step="2">
        <p class="rf-intro-eyebrow">{{ __('Two roles, one same goal') }}</p>
        <h2 class="rf-intro-title">{{ __('Both roles are fundamental') }}</h2>
        <p class="rf-intro-text">
          {{ __('UGF connects those seeking to grow with those who want to drive that growth.') }}
        </p>
        <div class="rf-roles-preview">
          <div class="rf-role-preview-item is-estudiante">
            <span class="rf-role-tag">{{ __('Student') }}</span>
            <p>{{ __('Represents the future, growth and talent that seeks to build a career.') }}</p>
          </div>
          <div class="rf-role-preview-item is-padrino">
            <span class="rf-role-tag">{{ __('Sponsor') }}</span>
            <p>{{ __('Represents the drive, opportunity and guidance to make student goals a reality.') }}</p>
          </div>
        </div>
        <div class="rf-intro-actions">
          <button type="button" class="rf-btn rf-btn-ghost" data-rf-prev>{{ __('Back') }}</button>
          <button type="button" class="rf-btn rf-btn-primary" data-rf-next>{{ __('Continue') }}</button>
        </div>
      </div>

      {{-- PASO 3 --}}
      <div class="rf-intro-step" data-step="3">
        <p class="rf-intro-eyebrow">{{ __('Last step') }}</p>
        <h2 class="rf-intro-title">{{ __('Are you ready to take this big step?') }}</h2>
        <p class="rf-intro-text">
          {{ __('Remember that once you choose your role, you will not be able to change it later.') }}
        </p>
        <div class="rf-intro-actions">
          <button type="button" class="rf-btn rf-btn-ghost" data-rf-prev>{{ __('Back') }}</button>
          <button type="button" class="rf-btn rf-btn-primary" id="rfIntroConfirm">{{ __('Yes, continue') }}</button>
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
      <h2>{{ __('How do you want to use the platform?') }}</h2>
      <p>{{ __('You will not be able to change this choice later, so take a moment before deciding.') }}</p>

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
            <strong>{{ __('I am a Student') }}</strong>
            <span>{{ __('I want career guidance and explore scholarships') }}</span>
          </span>
          <svg class="rf-role-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>

        <button type="button" class="rf-role-btn is-padrino" onclick="abrirModalRol('padrino')">
          <span class="rf-role-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.35-9.5-8.5C.7 9 2 5.5 5.5 5c2-.28 3.5 1 4.5 2 1-1 2.5-2.28 4.5-2C18 5.5 19.3 9 17.5 12.5 15 16.65 12 21 12 21z"/></svg>
          </span>
          <span>
            <strong>{{ __('I am a Sponsor') }}</strong>
            <span>{{ __('I want to support students financially') }}</span>
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
      <p class="rf-modal-sub">{{ __('Remember: you will not be able to change this choice later.') }}</p>
      <ul id="rolModalVentajas"></ul>
      <div class="rf-modal-actions">
        <button type="button" class="rf-btn rf-btn-ghost" onclick="cerrarModalRol()">{{ __('Cancel') }}</button>
        <button type="button" class="rf-btn rf-btn-primary" onclick="confirmarRol()">{{ __('Confirm and continue') }}</button>

<script>
  // Translations for JavaScript
  const translations = {
    studentTitle: '{{ __('You will register as a Student') }}',
    studentList: [
      '{{ __('You will receive a socioemotional test to discover which university degree best suits you.') }}',
      '{{ __('You can explore available scholarships and save them to your profile.') }}',
      '{{ __('Your profile will be visible to sponsors looking to support students in your area of interest.') }}',
      '{{ __('Access to the forum to ask questions to other students and sponsors.') }}',
    ],
    sponsorTitle: '{{ __('You will register as a Sponsor') }}',
    sponsorList: [
      '{{ __('You will see a step-by-step tutorial on how to provide financial support to university students.') }}',
      '{{ __('You can review student profiles and their areas of interest.') }}',
      '{{ __('You will have visibility of active scholarships and support programs on the platform.') }}',
      '{{ __('Access to the forum to connect directly with students and other organizations.') }}',
    ],
  };

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
      titulo: translations.studentTitle,
      lista: translations.studentList,
    },
    padrino: {
      titulo: translations.sponsorTitle,
      lista: translations.sponsorList,
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

  // Set title tag for HTML
  document.title = '{{ __('Select your role') }} — UGF';

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