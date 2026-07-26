<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil de {{ Auth::user()->usuario }} — UGF</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  @vite(['resources/css/homepage.css', 'resources/js/homepage.js'])
</head>
<body>

<!-- NAV (igual que homepage) -->
<nav id="navbar" class="scrolled">
  <div class="nav-inner">
    <a class="nav-logo" href="{{ route('home') }}"><span>UGF</span></a>
    <ul class="nav-links">
      <li><a href="{{ route('home') }}">Inicio</a></li>
    </ul>

    <div class="nav-actions">
      <span class="user-name" style="color: #fff; font-weight: 600; font-size: 0.95rem; margin-right: 8px;">
        Hola, {{ Auth::user()->usuario }}
      </span>

      <div class="user-menu" id="userMenu">
        <button type="button" class="user-menu-btn" id="userMenuBtn" title="Menú de cuenta" aria-haspopup="true" aria-expanded="false">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <div class="user-dropdown" id="userDropdown">
          <a href="{{ route('perfil') }}" class="user-dropdown-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            Perfil
          </a>
          <form action="{{ route('logout') }}" method="POST" class="user-dropdown-item-form">
            @csrf
            <button type="submit" class="user-dropdown-item user-dropdown-logout">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              Cerrar sesión
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

@php
  $avatarUrl = Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('img/default-avatar.png');
  $bannerUrl = Auth::user()->banner ? asset('storage/'.Auth::user()->banner) : null;
  $deptos = ['Ahuachapán', 'Santa Ana', 'Sonsonate', 'Chalatenango', 'La Libertad', 'San Salvador', 'Cuscatlán', 'La Paz', 'Cabañas', 'San Vicente', 'Usulután', 'San Miguel', 'Morazán', 'La Unión'];

  // % de perfil completado, para el "nivel de becario"
  $campos = [Auth::user()->nombre, Auth::user()->departamento, Auth::user()->avatar, Auth::user()->banner, Auth::user()->bio];
  $llenos = count(array_filter($campos));
  $porcentaje = round(($llenos / count($campos)) * 100);
@endphp

<div class="profile-page">

  @if (session('status'))
    <div class="profile-alert profile-alert-ok">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
    <div class="profile-alert profile-alert-error">
      <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif

  <!-- BANNER -->
  <div class="profile-banner" id="profileBanner" style="{{ $bannerUrl ? 'background-image:url('.$bannerUrl.')' : '' }}">
    <div class="profile-banner-overlay"></div>
    <label class="profile-banner-edit" title="Cambiar banner" form="profileEditForm">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
      </svg>
      Cambiar banner
      <input type="file" name="banner" id="bannerInput" accept="image/*" form="profileEditForm" hidden>
    </label>
  </div>

  <!-- HEADER: avatar + nombre + botón editar -->
  <div class="container profile-header">
    <div class="profile-avatar-wrap">
      <img src="{{ $avatarUrl }}" alt="Avatar" class="profile-avatar" id="avatarPreview">
      <label class="profile-avatar-edit" title="Cambiar foto de perfil" form="profileEditForm">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>
        </svg>
        <input type="file" name="avatar" id="avatarInput" accept="image/*" form="profileEditForm" hidden>
      </label>
    </div>

    <div class="profile-identity">
      <h1>{{ Auth::user()->nombre ?: Auth::user()->usuario }}</h1>
      <p class="profile-handle">@{{ Auth::user()->usuario }}</p>
      <p class="profile-bio" id="bioDisplay">{{ Auth::user()->bio ?: 'Aún no has escrito una biografía.' }}</p>
    </div>

    <button type="button" class="btn-outline profile-edit-toggle" id="editProfileBtn">Editar perfil</button>
  </div>

  <!-- BODY: sidebar de stats + contenido principal -->
  <div class="container profile-grid">

    <!-- SIDEBAR -->
    <aside class="profile-sidebar">
      <div class="profile-card">
        <div class="profile-level">
          <span class="profile-level-badge">{{ $porcentaje }}%</span>
          <div>
            <h4>Perfil de becario</h4>
            <span class="profile-level-sub">Completa tu perfil para destacar</span>
          </div>
        </div>
        <div class="profile-progress">
          <div class="profile-progress-fill" style="width: {{ $porcentaje }}%;"></div>
        </div>
      </div>

      <div class="profile-card">
        <h4 class="profile-card-title">Información</h4>
        <ul class="profile-info-list">
          <li><span>Departamento</span><strong>{{ Auth::user()->departamento ?: '—' }}</strong></li>
          <li><span>Correo</span><strong>{{ Auth::user()->correo }}</strong></li>
          <li><span>Miembro desde</span><strong>{{ Auth::user()->created_at?->format('M Y') ?? '—' }}</strong></li>
        </ul>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="profile-main">

      <!-- VISTA -->
      <div id="profileView">
        <div class="profile-section">
          <h3>Universidades de interés</h3>
          <div class="profile-empty">Aún no has guardado universidades. Explóralas desde el mapa de inicio.</div>
        </div>
        <div class="profile-section">
          <h3>Becas guardadas</h3>
          <div class="profile-empty">No tienes becas guardadas todavía.</div>
        </div>
        <div class="profile-section">
          <h3>Actividad reciente</h3>
          <div class="profile-empty">Tu actividad reciente aparecerá aquí.</div>
        </div>
      </div>

      <!-- EDICIÓN (oculto por defecto) -->
      <div id="profileEdit" class="profile-section" style="display:none;">
        <h3>Editar perfil</h3>
        <form id="profileEditForm" action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
          @csrf
          @method('PUT')

          <div class="profile-field">
            <label>Usuario</label>
            <input type="text" value="{{ Auth::user()->usuario }}" disabled>
          </div>

          <div class="profile-field">
            <label>Correo electrónico</label>
            <input type="email" value="{{ Auth::user()->correo }}" disabled>
          </div>

          <div class="profile-field">
            <label>Nombre completo</label>
            <input type="text" name="nombre" value="{{ old('nombre', Auth::user()->nombre) }}" required>
          </div>

          <div class="profile-field">
            <label>Departamento</label>
            <select name="departamento" required>
              @foreach($deptos as $d)
                <option value="{{ $d }}" {{ Auth::user()->departamento == $d ? 'selected' : '' }}>{{ $d }}</option>
              @endforeach
            </select>
          </div>

          <div class="profile-field">
            <label>Biografía</label>
            <textarea name="bio" rows="3" maxlength="160" placeholder="Cuéntanos algo sobre ti...">{{ old('bio', Auth::user()->bio) }}</textarea>
          </div>

          <div class="profile-field">
            <label>Nueva contraseña <span class="profile-field-hint">(opcional)</span></label>
            <input type="password" name="contrasena" placeholder="••••••••">
          </div>

          <div class="profile-form-actions">
            <button type="submit" class="btn-primary">Guardar cambios</button>
            <button type="button" class="btn-ghost" id="cancelEditBtn">Cancelar</button>
          </div>
        </form>
      </div>

    </main>
  </div>
</div>

<script>
  const editBtn    = document.getElementById('editProfileBtn');
  const cancelBtn  = document.getElementById('cancelEditBtn');
  const viewBlock  = document.getElementById('profileView');
  const editBlock  = document.getElementById('profileEdit');

  function openEdit() {
    viewBlock.style.display = 'none';
    editBlock.style.display = 'block';
    editBtn.textContent = 'Viendo perfil';
    editBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  function closeEdit() {
    viewBlock.style.display = 'block';
    editBlock.style.display = 'none';
    editBtn.textContent = 'Editar perfil';
  }

  editBtn.addEventListener('click', () => {
    editBlock.style.display === 'none' ? openEdit() : closeEdit();
  });
  cancelBtn.addEventListener('click', closeEdit);

  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  avatarInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    avatarPreview.src = URL.createObjectURL(file);
    openEdit();
  });

  const bannerInput = document.getElementById('bannerInput');
  const bannerEl = document.getElementById('profileBanner');
  bannerInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    bannerEl.style.backgroundImage = `url(${URL.createObjectURL(file)})`;
    openEdit();
  });
</script>

</body>
</html>