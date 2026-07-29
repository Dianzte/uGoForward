  @vite(['resources/css/navbar.css'])
  <link
      href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
      rel="stylesheet" />

  <nav id="navbar">
      <div class="nav-inner">
          <a class="nav-logo" href="{{ route('index') }}">
              <span>UGF</span>
          </a>

          <ul class="nav-links">
              <li><a href="{{ route('index') }}">Home</a></li>
              <li><a href="{{ route('becas.index') }}">Lista de becas</a></li>
              <li><a href="{{ route('foro.index') }}">Foro estudiantil</a></li>
          </ul>

          <div class="nav-actions">
              @auth
                  <!-- Nombre de Usuario -->
                  <span class="user-name" style="color: #fff; font-weight: 600; font-size: 0.95rem; margin-right: 8px;">
                      Hola, {{ Auth::user()->usuario }}
                  </span>

                  <!-- Menú desplegable -->
                  <div class="user-menu" id="userMenu">
                      <button type="button" class="user-menu-btn" id="userMenuBtn" title="Menú de cuenta"
                          aria-haspopup="true" aria-expanded="false">
                          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <line x1="3" y1="6" x2="21" y2="6" />
                              <line x1="3" y1="12" x2="21" y2="12" />
                              <line x1="3" y1="18" x2="21" y2="18" />
                          </svg>
                      </button>

                      <div class="user-dropdown" id="userDropdown">
                          <a href="{{ route('perfil') }}" class="user-dropdown-item">
                              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                  <circle cx="12" cy="7" r="4" />
                              </svg>
                              Perfil
                          </a>
                          <form action="{{ route('logout') }}" method="POST" class="user-dropdown-item-form">
                              @csrf
                              <button type="submit" class="user-dropdown-item user-dropdown-logout">
                                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                      <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                      <polyline points="16 17 21 12 16 7" />
                                      <line x1="21" y1="12" x2="9" y2="12" />
                                  </svg>
                                  Cerrar sesión
                              </button>
                          </form>
                      </div>
                  </div>
              @else
                  <!-- Invitado -->
                  <a href="{{ route('registro') }}" class="btn-ghost">Registrarse</a>
                  <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
              @endauth
          </div>

          <button class="burger" id="burger" aria-label="Menú">
              <span></span><span></span><span></span>
          </button>
      </div>

      <!-- Menú móvil (Estructura corregida) -->
      <div class="mobile-menu" id="mobileMenu">
          <a href="#servicios">Servicios</a>
          <a href="#universidades">Universidades</a>
          <a href="#nosotros">Nosotros</a>
          @auth
              <span style="color: var(--gold, #e8c847); padding: 0.4rem 0; font-weight: 600;">
                  Hola, {{ Auth::user()->usuario }}
              </span>
              <a href="{{ route('perfil') }}" class="user-dropdown-item">Perfil</a>
              <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                  @csrf
                  <button type="submit"
                      style="color:red; background:none; border:none; padding: 0.4rem 0; cursor: pointer; font-weight: 600;">
                      Cerrar sesión
                  </button>
              </form>
          @else
              <a href="{{ route('registro') }}" class="btn-ghost"
                  style="display: block; margin-bottom: 8px;">Registrarse</a>
              <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
          @endauth
      </div>
  </nav>
