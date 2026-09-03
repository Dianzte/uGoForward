<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('Profile of') }} {{ Auth::user()->usuario }} — UGF</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  @vite(['resources/css/settings.css', 'resources/css/temaUnido.css', 'resources/js/settings.js', 'resources/js/script.js'])
</head>
<body>

@include('navegacion.navbar')

@php
  $avatarUrl = Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('img/default-avatar.png');
  $bannerUrl = Auth::user()->banner ? asset('storage/'.Auth::user()->banner) : null;
  $deptos = ['Ahuachapán', 'Santa Ana', 'Sonsonate', 'Chalatenango', 'La Libertad', 'San Salvador', 'Cuscatlán', 'La Paz', 'Cabañas', 'San Vicente', 'Usulután', 'San Miguel', 'Morazán', 'La Unión'];

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

  <!-- FORMULARIO UNIFICADO ENVOLVIENDO BANNER, AVATAR Y DATOS -->
  <form id="profileEditForm" action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
    @csrf
    @method('PUT')

    <!-- BANNER -->
<div class="profile-banner" id="profileBanner" style="{{ auth()->user()->bannerImg ? 'background-image: url(' . asset('storage/' . auth()->user()->bannerImg->ruta) . ')' : '' }}">
      @unless (auth()->user()->bannerImg)
        <canvas class="ocean-scene" data-mode="banner" aria-hidden="{{ auth()->user()->bannerImg ? 'true' : 'false' }}"></canvas>
      @endunless
      <div class="profile-banner-overlay"></div>
      @unless (auth()->user()->bannerImg)
        <span class="profile-banner-hint" data-ocean-hint>{{ __('Click the sun or moon to change the mood') }}</span>
      @endunless
      <label class="profile-banner-edit" title="{{ __('Change banner') }}" for="bannerInput">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
        </svg>
        {{ __('Change banner') }}
        <input type="file" name="banner" id="bannerInput" accept="image/*" style="display: none;">
      </label>
    </div>

    <!-- HEADER: AVATAR Y DATOS -->
    <div class="container profile-header">
      <div class="profile-avatar-wrap">
        @if(auth()->user()->avatar)
          <img src="{{ asset('storage/' . auth()->user()->avatarImg->ruta)  }}" alt="Avatar" class="profile-avatar" id="avatarPreview">
        @else
          <img src="#" alt="Avatar" class="profile-avatar" id="avatarPreview">
        @endif
        <label class="profile-avatar-edit" title="{{ __('Change profile picture') }}" for="avatarInput">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>
          </svg>
          <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display: none;">
        </label>
      </div>

      <div class="profile-identity">
        <h1>{{ Auth::user()->nombre ?: Auth::user()->usuario }}</h1>
        <p class="profile-handle"><span>@</span>{{ Auth::user()->usuario }}</p>
        <p class="profile-bio" id="bioDisplay">{{ Auth::user()->bio ?: __('You have not written a biography yet.') }}</p>
      </div>

      <button type="button" class="btn-outline profile-edit-toggle" id="editProfileBtn">{{ __('Edit profile') }}</button>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container profile-grid">

      <!-- SIDEBAR -->
      <aside class="profile-sidebar">
        <div class="profile-card">
          <div class="profile-level">
            <span class="profile-level-badge">{{ $porcentaje }}%</span>
            <div>
              <h4>{{ __('Scholarship Profile') }}</h4>
              <span class="profile-level-sub">{{ __('Complete your profile to stand out') }}</span>
            </div>
          </div>
          <div class="profile-progress">
            <div class="profile-progress-fill" style="width: {{ $porcentaje }}%;"></div>
          </div>
        </div>

        <div class="profile-card">
          <h4 class="profile-card-title">{{ __('Information') }}</h4>
          <ul class="profile-info-list">
            <li><span>{{ __('Department') }}</span><strong>{{ Auth::user()->departamento ?: '—' }}</strong></li>
            <li><span>{{ __('Email') }}</span><strong>{{ Auth::user()->correo ?? Auth::user()->email }}</strong></li>
            <li><span>{{ __('Member since') }}</span><strong>{{ Auth::user()->created_at?->format('M Y') ?? '—' }}</strong></li>
          </ul>
        </div>
      </aside>

      <!-- MAIN -->
      <main class="profile-main">

        <!-- VISTA MODO LECTURA -->
        <div id="profileView">
          <div class="profile-section">
            <h3>{{ __('Universities of Interest') }}</h3>
            <div class="profile-empty">{{ __('You have not saved any universities yet. Explore them from the home map.') }}</div>
          </div>
          <div class="profile-section">
            <h3>{{ __('Saved Scholarships') }}</h3>
            <div class="profile-empty">{{ __('You do not have any saved scholarships yet.') }}</div>
          </div>
          <div class="profile-section">
            <h3>{{ __('Recent Activity') }}</h3>
            <div class="profile-empty">{{ __('Your recent activity will appear here.') }}</div>
          </div>
        </div>

        <!-- VISTA MODO EDICIÓN -->
        <div id="profileEdit" class="profile-section" style="display:none;">
          <h3>{{ __('Edit profile') }}</h3>

          <div class="profile-field">
            <label>{{ __('Username') }}</label>
            <input type="text" value="{{ Auth::user()->usuario }}" disabled>
          </div>

          <div class="profile-field">
            <label>{{ __('Email') }}</label>
            <input type="email" value="{{ Auth::user()->correo ?? Auth::user()->email }}" disabled>
          </div>

          <div class="profile-field">
            <label>{{ __('Full Name') }}</label>
            <input type="text" name="nombre" value="{{ old('nombre', Auth::user()->nombre) }}" required>
          </div>

          <div class="profile-field">
            <label>{{ __('Department') }}</label>
            <select name="departamento" required>
              <option value="" disabled {{ !Auth::user()->departamento ? 'selected' : '' }}>{{ __('Select your department') }}</option>
              @foreach($deptos as $d)
                <option value="{{ $d }}" {{ old('departamento', Auth::user()->departamento) == $d ? 'selected' : '' }}>{{ $d }}</option>
              @endforeach
            </select>
          </div>

          <div class="profile-field">
            <label>{{ __('Biography') }}</label>
            <textarea name="bio" rows="3" maxlength="160" placeholder="{{ __('Tell us about yourself...') }}">{{ old('bio', Auth::user()->bio) }}</textarea>
          </div>

          <div class="profile-field">
            <label>{{ __('New Password') }} <span class="profile-field-hint">{{ __('(optional)') }}</span></label>
            <input type="password" name="contrasena" placeholder="••••••••">
          </div>

          <div class="profile-form-actions">
            <button type="submit" class="btn-primary">{{ __('Save changes') }}</button>
            <button type="button" class="btn-ghost" id="cancelEditBtn">{{ __('Cancel') }}</button>
          </div>
        </div>

      </main>
    </div>
  </form>
</div>

<script>
  const editBtn    = document.getElementById('editProfileBtn');
  const cancelBtn  = document.getElementById('cancelEditBtn');
  const viewBlock  = document.getElementById('profileView');
  const editBlock  = document.getElementById('profileEdit');

  const editBtnTexts = {
    view: '{{ __('Edit profile') }}',
    edit: '{{ __('Viewing profile') }}'
  };

  function openEdit() {
    viewBlock.style.display = 'none';
    editBlock.style.display = 'block';
    editBtn.textContent = editBtnTexts.edit;
    editBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function closeEdit() {
    viewBlock.style.display = 'block';
    editBlock.style.display = 'none';
    editBtn.textContent = editBtnTexts.view;
  }

  editBtn.addEventListener('click', () => {
    editBlock.style.display === 'none' ? openEdit() : closeEdit();
  });

  cancelBtn.addEventListener('click', closeEdit);

  // Previsualización de Avatar
  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  avatarInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    avatarPreview.src = URL.createObjectURL(file);
    openEdit();
  });

  // Previsualización de Banner
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