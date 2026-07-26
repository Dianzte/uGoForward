<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UGF — Navega hacia tu futuro</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    
    @vite(['resources/css/app.css'])
    @vite(['resources/css/homepage.css', 'resources/js/homepage.js'])
</head>

<body>
    <!-- Pantalla de carga tipo gta 5 bien bellaco-->
    <div class="gta-loader-container" id="gta-loader">
        <div class="gta-background"></div>

        <div class="gta-content">
            <h1 class="ugf-title">UGF</h1>
            <p class="gta-slogan">Navega hacia el futuro</p>
        </div>

        <div class="gta-spinner-wrapper">
            <div class="gta-spinner"></div>
            <span class="gta-loading-text">CARGANDO</span>
        </div>
    </div>




    <!-- OCEAN CANVAS — fixed background layer -->
    <canvas id="oceanCanvas"></canvas>

    <!-- UNDERWATER OVERLAY — darkens + tints as you scroll -->
    <div id="underwaterOverlay"></div>

    <!-- BUBBLES CONTAINER -->
    <div id="bubblesContainer"></div>

    <!-- SEAWEED CONTAINER -->
    <div id="seaweedContainer"></div>

    <!-- FISH CONTAINER -->
    <div id="fishContainer"></div>

    @include('navegacion.navbar')


    <!-- SHIP ELEMENT (DOM, rides on waves) -->
    <div id="shipWrapper">
        <div id="ship">
            <div class="ship-sail sail-main"></div>
            <div class="ship-sail sail-small"></div>
            <div class="ship-body">
                <div class="ship-hull"></div>
                <div class="ship-deck"></div>
                <div class="ship-mast"></div>
                <div class="ship-flag"></div>
                <div class="ship-window"></div>
                <div class="ship-window w2"></div>
            </div>
            <div class="ship-wake"></div>
        </div>
    </div>

<<<<<<< HEAD



  <!-- OCEAN CANVAS — fixed background layer -->
  <canvas id="oceanCanvas"></canvas>

  <!-- UNDERWATER OVERLAY — darkens + tints as you scroll -->
  <div id="underwaterOverlay"></div>

  <!-- BUBBLES CONTAINER -->
  <div id="bubblesContainer"></div>

  <!-- SEAWEED CONTAINER -->
  <div id="seaweedContainer"></div>

  <!-- FISH CONTAINER -->
  <div id="fishContainer"></div>

<!-- NAV CORREGIDO -->
<nav id="navbar">
  <div class="nav-inner">
    <a class="nav-logo" href="{{ route('home') }}">
      <span>UGF</span>
    </a>
    
    <ul class="nav-links">
      <li><a href="#servicios">Servicios</a></li>
      <li><a href="#universidades">Universidades</a></li>
      <li><a href="#nosotros">Nosotros</a></li>
    </ul>
    
    <div class="nav-actions">
      @auth
        <!-- Nombre de Usuario -->
        <span class="user-name" style="color: #fff; font-weight: 600; font-size: 0.95rem; margin-right: 8px;">
          Hola, {{ Auth::user()->usuario }}
        </span>

        <!-- Menú desplegable -->
        <div class="user-menu" id="userMenu">
          <button type="button" class="user-menu-btn" id="userMenuBtn" title="Menú de cuenta" aria-haspopup="true" aria-expanded="false">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="6" x2="21" y2="6"/>
              <line x1="3" y1="12" x2="21" y2="12"/>
              <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
          </button>

          <div class="user-dropdown" id="userDropdown">
            <a href="{{ route('perfil') }}" class="user-dropdown-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              Perfil
            </a>
            <form action="{{ route('logout') }}" method="POST" class="user-dropdown-item-form">
              @csrf
              <button type="submit" class="user-dropdown-item user-dropdown-logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                  <polyline points="16 17 21 12 16 7"/>
                  <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Cerrar sesión
              </button>
            </form>
          </div>
        </div>
      @else
        <!-- Invitado -->
        <a href="{{ route('Register') }}" class="btn-ghost">Registrarse</a>
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
        <button type="submit" style="color:red; background:none; border:none; padding: 0.4rem 0; cursor: pointer; font-weight: 600;">
          Cerrar sesión
        </button>
      </form>
    @else
      <a href="{{ route('Register') }}" class="btn-ghost" style="display: block; margin-bottom: 8px;">Registrarse</a>
      <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
    @endauth
  </div>
</nav>
=======
    <!-- DEPTH INDICATOR -->
    <div id="depthMeter">
        <div class="depth-label">Profundidad</div>
        <div class="depth-bar">
            <div class="depth-fill" id="depthFill"></div>
        </div>
        <div class="depth-value" id="depthValue">0m</div>
    </div>

    <!-- HERO -->
    <section class="hero section-panel" id="inicio">
        <div class="container hero-content">
            <div class="badge" data-reveal>
                <span class="badge-dot"></span>
                Plataforma de Becas — El Salvador
            </div>
            <h1 data-reveal>
                Tu futuro <br />
                <em>No tiene limites</em>
            </h1>
            <br /><br />
            <p class="hero-sub" data-reveal>
                Conectamos estudiantes salvadoreños con oportunidades de beca,<br class="br-desktop" />
                padrinos comprometidos y universidades de todo el país.
            </p>
            <br /><br />
            <div class="hero-cta" data-reveal>
                <a href="#" class="btn-primary btn-lg">
                    Comenzar ahora
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="#servicios" class="btn-outline btn-lg">Explorar</a>
            </div>
            <div class="hero-stats" data-reveal>
                <div class="stat">
                    <span class="stat-num" data-count="24">0</span><span class="stat-plus">+</span>
                    <span class="stat-label">Universidades</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-num" data-count="14">0</span>
                    <span class="stat-label">Departamentos</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-num" data-count="1200">0</span><span class="stat-plus">+</span>
                    <span class="stat-label">Estudiantes</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-num" data-count="340">0</span>
                    <span class="stat-label">Padrinos</span>
                </div>
            </div>
        </div>

    </section>


    <!-- TRANSITION ZONE: surface to underwater -->
    <div class="dive-transition" id="diveTransition">
        <div class="surface-label">SUPERFICIE</div>
        <div class="depth-markers">
            <span>— 10m</span><span>— 20m</span><span>— 30m</span>
        </div>
    </div>
>>>>>>> e506ea30f5354ac1f743ac8809445d015fdc9d19

    <!-- SERVICIOS (underwater zone) -->
    <section class="section section-underwater" id="servicios">
        <div class="container">
            <div class="section-header" data-reveal>
                <span class="section-tag">¿Qué ofrecemos?</span>
                <h2>Descubre lo que hay<br />en las profundidades</h2>
            </div>
            <div class="services-grid">

                <div class="service-card" data-reveal>
                    <div class="service-icon" style="--icon-color:#7ec8e3;">
                        <svg viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="16" r="8" stroke="currentColor" stroke-width="2" />
                            <path d="M8 32c0-5.523 5.373-10 12-10s12 4.477 12 10" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3>Test Socioemocional</h3>
                    <p>Un análisis profundo de tu perfil emocional e intelectual que te guía hacia la carrera ideal
                        según tus fortalezas.</p>
                    <a href="#" class="card-link">Hacer el test →</a>
                </div>

<<<<<<< HEAD
 <!-- HERO -->
<section class="hero section-panel" id="inicio">
  <div class="container hero-content">
    <div class="badge" data-reveal>
      <span class="badge-dot"></span>
      Plataforma de Becas — El Salvador
    </div>

    <h1 data-reveal>
      Tu futuro <br />
      <em>No tiene límites</em>
    </h1>

    <p class="hero-sub" data-reveal>
      Conectamos estudiantes salvadoreños con oportunidades de beca,<br class="br-desktop" />
      padrinos comprometidos y universidades de todo el país.
    </p>

    <div class="hero-cta" data-reveal>
      <a href="#" class="btn-primary btn-lg">
        Comenzar ahora
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <a href="#servicios" class="btn-outline btn-lg">Explorar</a>
    </div>

    <div class="hero-stats" data-reveal>
      <div class="stat">
        <div>
          <span class="stat-num" data-count="24">0</span><span class="stat-plus">+</span>
        </div>
        <span class="stat-label">Universidades</span>
      </div>
      <div class="stat-divider"></div>
      <div class="stat">
        <div>
          <span class="stat-num" data-count="14">0</span>
        </div>
        <span class="stat-label">Departamentos</span>
      </div>
      <div class="stat-divider"></div>
      <div class="stat">
        <div>
          <span class="stat-num" data-count="1200">0</span><span class="stat-plus">+</span>
        </div>
        <span class="stat-label">Estudiantes</span>
      </div>
      <div class="stat-divider"></div>
      <div class="stat">
        <div>
          <span class="stat-num" data-count="340">0</span>
        </div>
        <span class="stat-label">Padrinos</span>
      </div>
    </div>
  </div>
</section>
  
=======
                <div class="service-card featured" data-reveal>
                    <div class="service-icon" style="--icon-color:#e8c847;">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M12 20l4 4 12-12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="20" cy="20" r="14" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </div>
                    <h3>Sistema de Padrinos</h3>
                    <p>Conecta con personas dispuestas a financiar tu educación bajo acuerdos claros y justos con
                        condiciones personalizadas.</p>
                    <a href="#" class="card-link">Conocer más →</a>
                </div>

                <div class="service-card" data-reveal>
                    <div class="service-icon" style="--icon-color:#4fc3f7;">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M8 28L20 10l12 18H8z" stroke="currentColor" stroke-width="2"
                                stroke-linejoin="round" />
                            <circle cx="20" cy="10" r="2" fill="currentColor" />
                        </svg>
                    </div>
                    <h3>Mapa de Universidades</h3>
                    <p>Explora todas las universidades de El Salvador que ofrecen becas, organizadas por departamento.
                    </p>
                    <a href="#" class="card-link">Ver mapa →</a>
                </div>

                <div class="service-card" data-reveal>
                    <div class="service-icon" style="--icon-color:#81c784;">
                        <svg viewBox="0 0 40 40" fill="none">
                            <rect x="8" y="8" width="24" height="28" rx="2" stroke="currentColor"
                                stroke-width="2" />
                            <path d="M14 16h12M14 22h8M14 28h6" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3>Tests de Práctica</h3>
                    <p>Prepárate con exámenes simulados por carrera y compara tus resultados con otros estudiantes.</p>
                    <a href="#" class="card-link">Practicar →</a>
                </div>

                <div class="service-card" data-reveal>
                    <div class="service-icon" style="--icon-color:#f48fb1;">
                        <svg viewBox="0 0 40 40" fill="none">
                            <rect x="6" y="8" width="28" height="26" rx="2" stroke="currentColor"
                                stroke-width="2" />
                            <path d="M14 8V6M26 8V6M6 18h28" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M12 25h4v4h-4z" fill="currentColor" opacity=".4" />
                        </svg>
                    </div>
                    <h3>Calendario de Becas</h3>
                    <p>Nunca pierdas una convocatoria. Ve las fechas de aplicación de cada universidad en tiempo real.
                    </p>
                    <a href="#" class="card-link">Ver calendario →</a>
                </div>

                <div class="service-card" data-reveal>
                    <div class="service-icon" style="--icon-color:#ce93d8;">
                        <svg viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="20" r="14" stroke="currentColor" stroke-width="2" />
                            <path d="M14 20c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <circle cx="15" cy="24" r="2.5" stroke="currentColor"
                                stroke-width="1.5" />
                            <circle cx="25" cy="24" r="2.5" stroke="currentColor"
                                stroke-width="1.5" />
                        </svg>
                    </div>
                    <h3>Red Social Académica</h3>
                    <p>Comparte y analiza respuestas con otros estudiantes. Aprende en comunidad.</p>
                    <a href="#" class="card-link">Unirse →</a>
                </div>

            </div>
        </div>
    </section>
>>>>>>> e506ea30f5354ac1f743ac8809445d015fdc9d19

    <!-- MAPA  -->
    <section class="section section-deep" id="universidades">
        <div class="container">
            <div class="section-header light" data-reveal>
                <span class="section-tag">Cobertura Nacional</span>
                <h2>Presencia en todo<br />El Salvador</h2>
                <p>Haz clic en cualquier departamento para ver las universidades disponibles con becas.</p>
            </div>
            <div class="map-wrapper" data-reveal>
            </div>
            <div class="">
                <div class="container-mapa">
                    <svg viewbox="0 0 1000 547" width="1000" xmlns="http://www.w3.org/2000/svg" id="svganimado">
                        <g id="features">
                            <path class="dept cursor-pointer" data-name="Ahuachapán" data-unis="2"
                                data-desc="Universidad Gerardo Barrios, UNIVO"
                                d="M183.1 180l3.2 1.3 6.8 4.3 1.3 1.2 0.7 0.8 0.4 0.8 0.8 3.2 0.4 0.9 0.6 0.9 1.3 1.3 1.6 1.2 1.3 1.4 0.1 0.1 0.8 1.5 0.3 0.9-1.1 6.9-7.5 25.9-6.7 2.8-8.2 2.3-4.6 0.7-1 0.7-0.8 1.1-0.4 2.5-0.1 1.6 0.9 7.2 0.6 2.3 0.7 1.7 0.9 1.8 0.3 0.9-0.2 1.3-7.6 11.7-0.7 2.1-0.2 1.6 3.1 10 0.4 2.2-0.5 1.5-1.1 1.8-2.8 3.4-1.1 2.1-0.6 1.6-0.3 3.6-0.3 1-4.8 8.6-0.7 1.9-2.1 8.6-1.9 0.8-3.1 0.4-11.3-0.7-2.3-0.5-0.8-0.5-2.7-2.6-4.9-5.7-0.8-0.6-1.5-1.1-0.9-0.4-1-0.4-1.4-0.1-1.5 0-2.1 0.4-1.3 0.4-1.1 0.5-0.9 0.6-1.1 0.9-2.8 3.4-5.9 9.4-0.9 1.4-25.4-11.6-29-14-6-25.1-0.1-6 0.9-6.3 2-6.1 3-5.6 4.5-4.6 10.3-5.5 4.6-3.8 3.4-5.4 2.5-5.6 3.2-5.5 5.6-4.9 36.1-25.3 7.9-8 4-2.7 16.7-6.3 5.5-0.4 6.8 1.8 9.8 7.6 5.5 2.3 5.5-3 0.1-2.2-0.3-0.6z"
                                id="SVAH" name="Ahuachapán">
                            </path>
                            <a>
                                <path class="dept cursor-pointer" data-name="Santa Ana" data-unis="4"
                                    data-desc="UNASA, UNICO, Galileo, UCO"
                                    data-universities='[
{
"name":"UNASA",
"image":"https://campussostenible.unasa.edu.sv/images/UNASA2024/UNASA_SUR_2024.jpg",
"description":"Universidad Autónoma de Santa Ana.",
"careers":"Medicina, Enfermería, Laboratorio Clínico.",
"website":"https://www.unasa.edu.sv"
},
{
"name":"Universidad Católica de Occidente",
"image":"https://i.ytimg.com/vi/28XYWdg6QJw/hq720.jpg?sqp=-oaymwE7CK4FEIIDSFryq4qpAy0IARUAAAAAGAElAADIQj0AgKJD8AEB-AH-CYAC0AWKAgwIABABGFcgYShlMA8=&rs=AOn4CLBxZoHvR9NMNLy6U48wmWikOfqrCQ",
"description":"Universidad privada.",
"careers":"Derecho, Ingeniería, Arquitectura.",
"website":"https://www.uco.edu.sv"
}
]'
                                    d="M194.1 232.6l7.5-25.9 1.1-6.9-0.3-0.9-0.8-1.5-0.1-0.1-1.3-1.4-1.6-1.2-1.3-1.3-0.6-0.9-0.4-0.9-0.8-3.2-0.4-0.8-0.7-0.8-1.3-1.2-6.8-4.3-3.2-1.3-2.4-5.6-0.3-3 1-3.2 16.1-28.4 7.5-8.2 9.4-7.3 9.9-4.6 40.3-9.7 2.5-2.4-0.1-6-1.2-1.6-4.8-2.4-1.6-1.6-0.7-2.5-0.7-5.9-0.5-1.8-2.2-4.2-1-1.5-2.2-2-2.6-1.2-7.9-1-2.3-5.5 2.2-3.3 3.8-2.7 2.4-3.8-1.1-4.5-2.7-3.8-1.7-4.4 2.5-6.1 5.3-4 5.5 0.5 4.1 4.1 1.1 7.2 5.1-3.7 9.3-11.2 2.6-1.1 4.1-1.8 5.9 0.8 4.3 2.3 4.2 1.5 5.9-1.2 8.6-8.8 3.8-1.5 0.5 7.5 1.9-3 0.4-0.9 10.8 7.9 0.1 0.1 5.8 0.9 14.3 13.4 2.5 2.8 0.5 0.7 1.2 2.9 1.7 2.9 0.8 0.8 1.3 1.1 5.6 2.9 0.8 0.7 1.8 2.4 6.9 4.4-4.8 14-1.5 7.8-3.3 3.7-14 5.7 0 7.7 0.3 4.3-0.1 1.5-0.7 1.8-7.3 10.1-1.3 1.3-0.8 0.6-0.8 0.5-1 0.3-1 0.3-1.2 0.2-3.8 0.2-1 0.2-1 0.3-0.9 0.4-0.8 0.5-0.7 0.6-4.4 8.1-0.9 1.2-0.7 0.7-0.9 0.5-0.9 0.4-7.1-0.5-7.2 24.8 1.1 8.4 4.2 3.8 5.6 1.1 5.6 0 4.4-1.2-4 21.4-0.7 5.5 0.1 1.2-0.6 7.5-0.4 1.2-0.8 1-1.9 0.9-1.4 0-1.3 0-1 0.1-0.8 0.4-3 4.9-1.2 0.8-1.1 0.1-0.9-0.3-1-0.1-0.9 0.3-0.8 0.5-0.6 0.8-0.5 2.1-0.5 10.4 0.4 6.9-0.7 8.3-6.6 18-1.8 10.7-17.9-3.4-4.7-1.6-0.6-0.8-0.6-0.8-2.2-5.6-5-4.7-8-5.9-3.6-2.2-2.5-1.1-1 0.2-0.8 0.6-0.6 0.7-1 1.6-1.2 2.8-0.5 2.1 0 3.5-0.3 0.9-0.8 0.9-1.4 0.7-2.7 0.6-1.7-0.4-1.2-0.4-0.9-0.7-0.6-0.7-0.5-0.9-0.4-1.2-0.9-1.3-1.7-1.8-2.4-0.4-1.2-0.8-0.5-0.8 0.2-1 0.4-0.8 0.7-0.8 1.4-1.2 0.6-0.6 0.5-0.9 0.3-1.1 0.1-1-0.4-2-1.7-2.8-2.3-2.1-1.2-1.3-0.8-1.1-1.2-2.8-1.1-1.5-6.1-6-2.2-1.6-1.7-0.8-1.2 0-1.1 0.1-2.1 0.6-3.4 0.1-8.7-2.2z"
                                    id="SVSA" name="Santa Ana">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="Chalatenango" data-unis="1"
                                    data-desc="Universidad Gerardo Barrios"
                                    d="M324.5 181.7l-4.4 1.2-5.6 0-5.6-1.1-4.2-3.8-1.1-8.4 7.2-24.8 7.1 0.5 0.9-0.4 0.9-0.5 0.7-0.7 0.9-1.2 4.4-8.1 0.7-0.6 0.8-0.5 0.9-0.4 1-0.3 1-0.2 3.8-0.2 1.2-0.2 1-0.3 1-0.3 0.8-0.5 0.8-0.6 1.3-1.3 7.3-10.1 0.7-1.8 0.1-1.5-0.3-4.3 0-7.7 14-5.7 3.3-3.7 1.5-7.8 4.8-14-6.9-4.4-1.8-2.4-0.8-0.7-5.6-2.9-1.3-1.1-0.8-0.8-1.7-2.9-1.2-2.9-0.5-0.7-2.5-2.8-14.3-13.4 14.5 2.3 11.6 5.6 19.2 4.1 9.5 6.7 17.3 5.2 3.6-3.2 5.4-10.5 3-3.1 3.3-0.9 2.2 0.4 1.9 1.7 2.1 3 1.9 3.5 0.8 3.9-0.3 3.9-1.5 3.8 4.3 3.7 12.8 1.5 6 3 2.4 4.8 1.5 11.9 1.7 3.5 9.5 7.2 3.4 4.2 3.4 5.7 1.4 4.9 0.2 1.8 0.4 3.2 1.2 4.1 3.3 2.4 3.7-0.8 4.4-2.6 5.3-2.3 5.8 0.1 9.3 6 7.9 9.9 5.6 11.6 2.5 10.9 6.6-1 16.4 1.3 4.5-1.8 3.9-2.4 3.3-0.3 2.7 4.4-0.4 6.5-2.2 6.4-0.2 5.5 5.9 3.8 8 1.1 1.8 0.6 2.7 2.4 1.8 2.6 0.9 3.2-0.2 2-17.3 2.6-7.7 3.8-2.9 3.8-2.5 2-4.5 2.1-23.1 7.6-5.6 2.1-5.2 1.3-9.8-0.9-11.1 0.3-6.2-0.7-5.8-2.2-6.8 0.5-8-3.8-1.4-0.9-1.7-1.6-2-2.9-1-2.1-0.6-1.8-0.3-1-0.1-0.9 0-1.1 0.3-1.6 0-0.1-0.2-0.7-0.7-1-8.2-9.8-1.7-3-0.6-0.8-6.3-4.5-2.7-2.4-1.9-1.1-9.7-3.5-4.7-2.4-1.3-0.1-1.2 0-6.6 3.4-10.4 4.8-4.2-1-5.3-4.2-2.4 4.8-5.2-0.1-7.8-3.4-2.9 1-4.9 3.5-1.5 0.8-2.7-0.4-3.6-1.7-1.2-0.3-6.1-0.9-6.8-3.9-3.9-0.8-3.6 2.8-3.5 1.9-4.4 0.9-3.1 1.2-1.2 2.6-1.7 2.7z"
                                    id="SVCH" name="Chalatenango">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="Cabañas" data-unis="1"
                                    data-desc="UNIVO sede Cabañas"
                                    d="M490.3 217.5l11.1-0.3 9.8 0.9 5.2-1.3 5.6-2.1 23.1-7.6 4.5-2.1 2.5-2 2.9-3.8 7.7-3.8 17.3-2.6 0 0.7 2.3-0.9 9.5-1.5 9.6 0.7 28.4 9.1 16.6 1.2 3.6 1.6 3.4 3.3-0.3 1.2-0.1 0.9-0.2 0.2-0.4 0.3-0.1 0-1.5-0.2 0.4 0.9 1.9 4.2-0.2 4.5-1.3 4.7-0.8 5.7 1 3.7 4.1 10.2 0.3 6.1-3.3 5.5-0.7 0.6-1.7 1.5 2.4 4.8-0.6 4.6-2.6 3.9-4.1 2.6 0.8 5.3-3.5 5.4-3.4 6.7 0 0.1-6.1 4.1-3.5-0.9-9.5-1.2-1.4-0.5-0.7-0.6-0.5-0.8-0.3-1-0.4-2.1-0.6-0.9-1-0.5-2.6-0.7-0.9-0.4-2.6-1.5-3.9-1.4-0.9-0.5-6.9-5.1-0.9-0.4-4.1-0.8-5.2-0.3-2.4-0.4-12 0.1-2.7-0.2-1.8-0.5-5.4-3.9-1.2 0.1-1.4 0.7-1.9 2.5-1.2 1.3-1.3 0.8-2.2-0.2-2.7-0.6-2.5 0.6-11.7 5.2-3.6 1.2-2.6 0.4-3.9-1.3-2.2-0.5-2.4-0.1-1 1.4-0.7 1.2-0.5 11.1-13-3.6-3.4-1.9-0.4-1-2.4-3.7-3.6-3.9-1.5-2-0.9-1.7-0.5-2.5-0.5-1.5-1.9-3.4-0.4-1.6-0.3-1.7-1.4-3-1.8-2.1-3.1-2.1-2.3-2.4-7.3-10.4-1.8-3.3-0.9-2.5 0.2-1.2 0.3-1 0.3-0.9 0.6-0.9 0.6-0.6 0.8-0.6 5-2.9 1.4-1.2 1.3-1.4 1.1-1.6 0.4-0.9 5.5-8.8z"
                                    id="SVCA" name="Cabañas">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="Morazán" data-unis="2"
                                    data-desc="UGB, Gerardo Barrios"
                                    d="M736.4 228.6l1.5-2.6 2.5-2.8 4.5-1.9 3.3 0.5 2.4-0.5 1.8-5.3-0.6-1-3.4-3.1-0.8-1.4-0.3-7.7 0.1-1.3 3.7-1.4 5.7 0.6 0.9 0.2 9 2 6 0.2 19.6-2.4 9.9 0.6 4.3 3.6 5.5 14.3 5.4 6.6 7 6.3 6.1 7.4 3.1 9.5 17.1-8.5 5.6-1.5 0.9 0.1 1.2 7.6 1.6 6.3 0.7 10.9-1.5 23.5 0.1 1.3 0.2 1 0.5 0.8 1.7 2.2 0.5 0.9 0.4 0.9 0.3 0.9 0.4 2.4-0.2 7.4-1.1 8.9-2.2 10.4-8.6 22.3-0.7 7.3-2.7 4.2-11.6 12.4-8.1-1.3-3.9 0.2-14.7 4.6-2.8 0.3-1.8-0.3-0.6-0.6-0.6-1.1-0.3-0.8-0.9-1-1.3-1.2-5.5-3.3-0.8-0.6-0.6-0.8-0.4-0.8-1.1-6.9-0.6-2.1-1.4-2.6-1.6-2.1-1.3-0.8-1.4-0.5-14.6 1.2-2.1 0.4-1.9 0.8-3 1.8-2.1 0.6-0.6-0.5-0.1-0.6 0.5-0.9 2.2-3 0.4-0.9 0.1-1-0.6-1-0.9-0.9-2.7-1.7-0.8-0.7-0.6-0.7-0.3-0.9-0.3-1.1-0.2-1.1-0.8-1.5-1.4-1.9-3.5-2.6-1.4-1.4-0.7-2.3-1.2-1.9-4.8-4.3-1.9-2.3-1.2-1.8-0.6-9.7 0-1 0.4-0.8 0.7-0.7 12.3-8.9 1.7-1 2.9-1 0.9-0.5 0.7-0.7 0.6-0.7 0.3-1.1 0-1.4-0.7-2.3-1-1.5-0.8-1-14.2-9.7-2.1-1.9-1.4-1.5-0.3-1-0.3-1-0.1-3.8 0.3-2.1 0.2-0.7 0.2-0.7 0.5-0.8 1.1-1.4 0.7-0.6 0.8-0.5 0.6-0.7 0.6-0.8 0.4-0.8 0.4-1 0.2-1.1 0-2.3-1.2-3.2-1.5-2.9-0.6-1.7-0.1-1.3-0.7-3.1-4.4-10.4-2.1-2.9z"
                                    id="SVMO" name="Morazán">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="San Miguel" data-unis="5"
                                    data-desc="UES Oriente, UGB, UNICO, UNIVO, Modular"
                                    d="M639.5 290.4l0-0.1 3.4-6.7 3.5-5.4-0.8-5.3 4.1-2.6 2.6-3.9 0.6-4.6-2.4-4.8 1.7-1.5 0.7-0.6 10-0.5 7-4.3 6.8-2.9 15.2-3.3 3.5 0.2 6 2.3 2.8 0.3 0.4-1.1 0-2.3 0.3-2.4 1.2-1.4 2.4-0.1 3 0.5 3 0.9 2.4 1 2.6-4.8 4.1-1.5 4.5-0.4 4.1-1.4 3-2.8 1.2-2.3 2.1 2.9 4.4 10.4 0.7 3.1 0.1 1.3 0.6 1.7 1.5 2.9 1.2 3.2 0 2.3-0.2 1.1-0.4 1-0.4 0.8-0.6 0.8-0.6 0.7-0.8 0.5-0.7 0.6-1.1 1.4-0.5 0.8-0.2 0.7-0.2 0.7-0.3 2.1 0.1 3.8 0.3 1 0.3 1 1.4 1.5 2.1 1.9 14.2 9.7 0.8 1 1 1.5 0.7 2.3 0 1.4-0.3 1.1-0.6 0.7-0.7 0.7-0.9 0.5-2.9 1-1.7 1-12.3 8.9-0.7 0.7-0.4 0.8 0 1 0.6 9.7 1.2 1.8 1.9 2.3 4.8 4.3 1.2 1.9 0.7 2.3 1.4 1.4 3.5 2.6 1.4 1.9 0.8 1.5 0.2 1.1 0.3 1.1 0.3 0.9 0.6 0.7 0.8 0.7 2.7 1.7 0.9 0.9 0.6 1-0.1 1-0.4 0.9-2.2 3-0.5 0.9 0.1 0.6 0.6 0.5 2.1-0.6 3-1.8 1.9-0.8 2.1-0.4 14.6-1.2 1.4 0.5 1.3 0.8 1.6 2.1 1.4 2.6 0.6 2.1 1.1 6.9 0.4 0.8 0.6 0.8 0.8 0.6 5.5 3.3 1.3 1.2 0.9 1 0.3 0.8 0.6 1.1 0.6 0.6 1.8 0.3 2.8-0.3 14.7-4.6 3.9-0.2 8.1 1.3-2.5 3.3-0.8 4.9 0.2 11.7 1.5 5.6 0 1.2-0.4 1.3-1.4 1.7-1.1 0.9-1 0.8-1.6 0.9-0.7 0.6-0.6 1.5-0.3 2.5 0.2 9.9 1.1 6.8 0.3 1 0.5 0.9 3.5 5.6 0.4 0.9 0.4 1 0.1 0.9-4.8 27.8 0.1 1.6 0.6 2.2-0.3 2.4-4.9 16.1-0.8 0.9-1.8 0.5-1.2 0-1.3-0.3-3.9-1.4-3.2-1.9-3.1-2.3-0.8-0.3-1 3.8-0.5 2.9-2.1 30.2 0 0.1-2.6-0.5-2.5 0-2.8 1.1-23-26.8-1-0.4-1-0.2-1.2-0.1-10.2 1.6-0.9-0.2-0.7-0.8-0.1-1.9 0.2-1.2 0.4-1.3 2.8-5.1-0.2-1.1-0.9-1.2-2.5-1.3-1.4-1.2-0.9-1.4 0-5.7-0.2-1.1-0.3-1.2-0.7-1.1-1.2-1.4-1.6-0.2-1.2 0.1-3.5 1.9-1.9 0.8-1.1 0.3-1 0-1.1-0.1-0.9-0.4-0.8-0.5-1.5-1.2-0.8-0.5-0.9-0.4-1.1-0.1-0.9 0.1-1.1-0.8-1.6-1.4-3.3-4-1.9-1.6-1.7-1-7.4-0.8-2-0.5-2.8-1.3-0.8-0.5-0.7-0.5-2.6-3.4-3.8-6.1-2-4.1-2-6.9-0.6-3-0.2-2.3 0.1-2.5-0.2-1.2-0.6-1-1.5-0.5-1.8 0.7-0.6-0.1-0.6-0.4-0.4-1.2-1.2-7.1-0.1-2.5-0.6-5.2-0.1-1.1 0.1-1.2 0.3-1 0.6-0.7 0.7-0.2 0.6 0.2 0.4 0.4 0.5 0.6 0.6 0.5 0.7 0 0.5-0.6 0.4-1 0.2-2.4-0.1-2.3 0.1-1.2 0.3-1 0.5-0.9 0.6-0.7 2-1.9 0.1-0.1 0.5-1 0.1-0.8-0.2-1.5-1.5-5.1-0.3-2 0-1.6 0.8-3 0-0.9-0.5-1.5-4.5-9.6-0.3-1-0.2-1.1 0-2.3 0.1-1.2 0.3-1 0.5-0.8 0.6-0.7 0.8-0.5 2.1-0.7 0.8-0.4 0.6-0.7 0.6-0.8 0.3-1 0.2-1-0.2-1.4-0.5-1.6-1.1-2.7-0.7-3.2 0-1.2 0.2-1.1 1.8-5 0-1.1-1.2-1-2.3-0.7-9.2-1.2-2.9-1-3.3-2.3-2.5-2.4-3.2-5-2.3-2.9-4.4-0.3-15.4 1.8-4.3-6.8-11.6-11.6-1-9.1z"
                                    id="SVSM" name="San Miguel">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="La Unión" data-unis="1"
                                    data-desc="UGB sede La Unión"
                                    d="M836.2 370.7l11.6-12.4 2.7-4.2 0.7-7.3 8.6-22.3 2.2-10.4 1.1-8.9 0.2-7.4-0.4-2.4-0.3-0.9-0.4-0.9-0.5-0.9-1.7-2.2-0.5-0.8-0.2-1-0.1-1.3 1.5-23.5-0.7-10.9-1.6-6.3-1.2-7.6 2.1 0.5 5.5 3.6 2.6 0.7 1.7-0.8 4.8-3.8 3-1 3.7 0.2 7.4 1.6 3.6-0.3 3.6-2.6 3.1-3.9 3.8-3.2 5.6-0.2 3.6 2.8 3.6 10.2 3.1 4.6 1.9 1.1 5.3 2.1 2.3 1.2 5.9 5.2 10.6 9.1 6.6 2.7-3.3 5.6-7.6 17.6-1.9 6.3 2.4 6.4-1.8 4.1-3.8 3.6-3.3 4.7-1.3 6.9 0.3 14.3-0.9 7-9 21.9-2.2 10.6 4 8.8 3.8 1.4 8-2.6 5.4 2.1 2.9 3.4 2 4.6 0.6 5.2-0.9 4.9-6.4 7.4-29.6 13.6-0.3-0.1-2.9-1.8-1.9-3.1-2.8-8.3-10.6 18.4-2.7 10.8 6.9 4.8 4.4 1.7 12.4 12 7.1 4.8 0.6 2.2 0 4.7-3 8.2-7.5 5.9-27.9 14.1-7.6 6.1-1.3 6.1 8.7 6-13.3 3.1-51.7-3.6-9.6-1.8 0-0.1 2.1-30.2 0.5-2.9 1-3.8 0.8 0.3 3.1 2.3 3.2 1.9 3.9 1.4 1.3 0.3 1.2 0 1.8-0.5 0.8-0.9 4.9-16.1 0.3-2.4-0.6-2.2-0.1-1.6 4.8-27.8-0.1-0.9-0.4-1-0.4-0.9-3.5-5.6-0.5-0.9-0.3-1-1.1-6.8-0.2-9.9 0.3-2.5 0.6-1.5 0.7-0.6 1.6-0.9 1-0.8 1.1-0.9 1.4-1.7 0.4-1.3 0-1.2-1.5-5.6-0.2-11.7 0.8-4.9 2.5-3.3z m105.9 136.3l-0.3-4.9 2.7-2 5.2 1.7 3.2 7.1 0.2 5.9 1.4 3.9-3.1 1.8-4.9-1.8-3.2 1.5 0-4.2-2-4.1 0.8-4.9z m-14.8-19.2l2.9 1.8 2.5 5.6-2.2 3.2-2.5 2.4-4.9-4.4-1.2-6.1 2.2-2.2 3.2-0.3z"
                                    id="SVUN" name="La Unión">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="Sonsonate" data-unis="3"
                                    data-desc="UNICO, USONSONATE, UNIVO"
                                    d="M106 326.8l0.9-1.4 5.9-9.4 2.8-3.4 1.1-0.9 0.9-0.6 1.1-0.5 1.3-0.4 2.1-0.4 1.5 0 1.4 0.1 1 0.4 0.9 0.4 1.5 1.1 0.8 0.6 4.9 5.7 2.7 2.6 0.8 0.5 2.3 0.5 11.3 0.7 3.1-0.4 1.9-0.8 2.1-8.6 0.7-1.9 4.8-8.6 0.3-1 0.3-3.6 0.6-1.6 1.1-2.1 2.8-3.4 1.1-1.8 0.5-1.5-0.4-2.2-3.1-10 0.2-1.6 0.7-2.1 7.6-11.7 0.2-1.3-0.3-0.9-0.9-1.8-0.7-1.7-0.6-2.3-0.9-7.2 0.1-1.6 0.4-2.5 0.8-1.1 1-0.7 4.6-0.7 8.2-2.3 6.7-2.8 8.7 2.2 3.4-0.1 2.1-0.6 1.1-0.1 1.2 0 1.7 0.8 2.2 1.6 6.1 6 1.1 1.5 1.2 2.8 0.8 1.1 1.2 1.3 2.3 2.1 1.7 2.8 0.4 2-0.1 1-0.3 1.1-0.5 0.9-0.6 0.6-1.4 1.2-0.7 0.8-0.4 0.8-0.2 1 0.5 0.8 1.2 0.8 2.4 0.4 1.7 1.8 0.9 1.3 0.4 1.2 0.5 0.9 0.6 0.7 0.9 0.7 1.2 0.4 1.7 0.4 2.7-0.6 1.4-0.7 0.8-0.9 0.3-0.9 0-3.5 0.5-2.1 1.2-2.8 1-1.6 0.6-0.7 0.8-0.6 1-0.2 2.5 1.1 3.6 2.2 8 5.9 5 4.7 2.2 5.6 0.6 0.8 0.6 0.8 4.7 1.6 17.9 3.4 4.3 1.4 0.9 0.4 0.8 0.6 0.7 0.6 0.5 0.8 0.3 1 0.2 1-0.5 1.5-1 1.5-2.7 2.4-2.4 1.7-5.5 3.2-2.1 1.9-4.4 5.5-1.5 1.2-1.5 0.6-2.6 0.3-0.9 0.2-1.5 0.9-0.8 0.7-0.8 1.4-0.8 2.1-1.1 4.6-0.6 4.5 0.5 8.5-0.1 1.3-1 1.8-1.9 2.6-8.2 8.2-0.7 0.5-0.8 0.8-0.9 1.2-0.8 1.9-0.7 3.2-0.5 4.7-1.1 1.5-1.6 1.9-9.5 8.4-2 3.2-4 9.1-1 2.5-2.6-0.6-23.8-7.8-12.6 2.6-35.6 0.4-5.7-3.9-3.8-14.2-1.4-8.9-4.7-3.9-7.8-4.8-30.4-17.3-0.8-0.4z"
                                    id="SVSO" name="Sonsonate">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="La Libertad" data-unis="6"
                                    data-desc="UCA, UJMD, UDB, Tecnológica, UTEC, Modular"
                                    d="M292.5 284.3l1.8-10.7 6.6-18 0.7-8.3-0.4-6.9 0.5-10.4 0.5-2.1 0.6-0.8 0.8-0.5 0.9-0.3 1 0.1 0.9 0.3 1.1-0.1 1.2-0.8 3-4.9 0.8-0.4 1-0.1 1.3 0 1.4 0 1.9-0.9 0.8-1 0.4-1.2 0.6-7.5-0.1-1.2 0.7-5.5 4-21.4 1.7-2.7 1.2-2.6 3.1-1.2 4.4-0.9 3.5-1.9 3.6-2.8 3.9 0.8 6.8 3.9 6.1 0.9 1.2 0.3 3.6 1.7-0.4 13-2 8.3-0.1 2.1 0.2 1.4 2.3 1.8 0.9 1.3 1.1 2 1.9 4.4 1.1 2.1 1 1.4 1.6 1 1.8 0.9 3 0.9 1.1 0.1 3.5-0.3 1.2 0 1.1 0.2 1.1 0.2 1 0.4 1.2 0.7 1.4 1.1 2 1.1 0.6 0.8 0.1 0.6-1 2.6-0.5 2.2-0.1 0.4-0.3 0.3-2.8-0.1-1 0.3-0.7 0.7-0.4 1.2-1.9 21.6-0.5 2.2-0.4 0.9-0.8 0.5-1.9 0.7-0.9 0.5-0.7 0.6-1.2 1.4-0.7 1.9-0.5 1.8-0.7 6.8-0.8 3-1.2 2.8-0.7 3.6-0.4 6.2-0.3 1.6-0.3 1-2.6 4.9-1.7 4.8 0.2 1.5 0.6 0.8 1 0.3 1.2 0.1 1.9 2.5-0.4 3.8 0.3 3.7 0.4 1 0.5 0.8 0.6 0.7 1.5 1.1 0.7 0.9 0.4 1.3 0.7 3 0.6 1.7 0.7 1.1 0.8 0.6 5.4 2.9 0.7 0.5 1.6 1.4 2.5 3.4 0.4 1.4-0.2 1.2-1.2 2.3-0.9 2.2 0.1 1.2 0.8 0.8 2.9 1.4 0.4 0.9-0.2 0.9-0.6 0.7-0.7 0.6-0.6 2.2-0.2 3.7 0.7 15.9-0.2 1.2-2.4 6.1-0.8 3.4-0.1 1.8 0.2 1.4 6 9.8 2.1 4.6 0.6 0.7 0.8 0.4 1.1 0.2 1.2-0.1 2.1-0.5 3.9-1.3 2.2-0.3 1.4 0.2 1 0.3 2.8 1.2 1.3 6.3 1.1 1.6 0.9 0 1.6 0.1 2 0.5 3.7 1.5 1.3 1.3 0.3 1.1-1 1.7-1.8 2.2-0.5 0.8-0.6 0.7-2.9 5.2-1 2.3-1.2 3.6-17.9-11-25.5-11.3-27.6-6.6-12.8 0-9.8-2.3-53.3 0.5-22.8-5.8-8.1-1.5 1-2.5 4-9.1 2-3.2 9.5-8.4 1.6-1.9 1.1-1.5 0.5-4.7 0.7-3.2 0.8-1.9 0.9-1.2 0.8-0.8 0.7-0.5 8.2-8.2 1.9-2.6 1-1.8 0.1-1.3-0.5-8.5 0.6-4.5 1.1-4.6 0.8-2.1 0.8-1.4 0.8-0.7 1.5-0.9 0.9-0.2 2.6-0.3 1.5-0.6 1.5-1.2 4.4-5.5 2.1-1.9 5.5-3.2 2.4-1.7 2.7-2.4 1-1.5 0.5-1.5-0.2-1-0.3-1-0.5-0.8-0.7-0.6-0.8-0.6-0.9-0.4-4.3-1.4z"
                                    id="SVLI" name="La Libertad">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="La Paz" data-unis="2"
                                    data-desc="UNIVO, UGB sede La Paz"
                                    d="M413 423.6l1.2-3.6 1-2.3 2.9-5.2 0.6-0.7 0.5-0.8 1.8-2.2 1-1.7-0.3-1.1-1.3-1.3-3.7-1.5-2-0.5-1.6-0.1-0.9 0-1.1-1.6-1.3-6.3 0.8-3.2 1.1-0.7 2.7-3 1.9-4.1 3.2-9.6 0.8-4.2 0.2-2.7-0.7-1.9-2.9-5.1-1.1-3.1-0.7-3.3 0.2-1.8 0.5-1.2 0.9-0.5 1-0.2 1.2 0 1.1 0.2 6 1.9 1.2 0.2 1.1 0.1 1.9-1.5 11.3-14.4 13.6-12.8 16.5 6.1 6.5 3.5 7.1 2.2 1.3 0 1.7-0.3 0.7-0.2 0.8-0.4 0.6-0.7 0.8-1.8 0.7-0.8 0.8-0.4 1-0.3 4.7-0.6 2.1-0.7 2.6-1.6 2.8 7.6 0.1 6.3-0.4 6.6 0.4 2.6 0.7 1.6 1.2 0.1 1.2 0 1.2-0.2 8.9-2.8 1.6-0.1 1.7 0.3 2.9 1.1 1 1.3 9.3 19.8 0.3 1.1 0.1 1.1-0.1 2.5-0.4 2.2-2.9 7.9-0.2 0.9-0.3 0.9-0.3 1.3 0 0.6 0 0.1-1.6 8.4 0 1.8 0.5 6.7 0.5 2.2 0.6 1.6 2.4 2.7 2.3 1.8 3.3 2 2.6 2.5 2.4 3.2 0.9 2 0.4 1.6-0.2 1.2-0.5 2.2-0.3 0.9-0.5 0.9-2.3 1.7-0.6 0.7-0.5 0.8-0.3 1-0.1 1.2-0.1 1.2 1.7 15.8-0.1 2.2-0.5 0.8-0.6 0.7-0.7 0.8-0.8 0.5-3.5 1.7-2.7 1.1-1.7 1-1.5 1.2-0.6 0.7-0.5 0.8-0.5 1-0.3 0.9-0.3 1.1-0.9 11.6 0 0.1-87.6-40.9-26.6-16.4z"
                                    id="SVPA" name="La Paz">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="San Vicente" data-unis="2"
                                    data-desc="UES San Vicente, UNIVO"
                                    d="M520.2 293.8l0.5-11.1 0.7-1.2 1-1.4 2.4 0.1 2.2 0.5 3.9 1.3 2.6-0.4 3.6-1.2 11.7-5.2 2.5-0.6 2.7 0.6 2.2 0.2 1.3-0.8 1.2-1.3 1.9-2.5 1.4-0.7 1.2-0.1 5.4 3.9 1.8 0.5 2.7 0.2 12-0.1 2.4 0.4 5.2 0.3 4.1 0.8 0.9 0.4 6.9 5.1 0.9 0.5 3.9 1.4 2.6 1.5 0.9 0.4 2.6 0.7 1 0.5 0.6 0.9 0.4 2.1 0.3 1 0.5 0.8 0.7 0.6 1.4 0.5 9.5 1.2 3.5 0.9 6.1-4.1 1 9.1 11.6 11.6 4.3 6.8-23.6 17.2-2.1 6.8-5.6 7.7-7.5 4.8-8.3-1.9-10.8 13.4-0.5 2.4-6.2 1.1-2.1 3-0.9 11.7-3.2 11.5-17.1 32.6-1.6 4.1-0.9 4.9-0.5 12-0.9 2.6-6.6 6.4-3.5 5.2-2.2 4.2-3.6 2.9-7.3 1.1-4.5 1.7-2.8 3.8-1.5 5.5 0 0.8 0.2 0.1-5.6-2.6 0-0.1 0.9-11.6 0.3-1.1 0.3-0.9 0.5-1 0.5-0.8 0.6-0.7 1.5-1.2 1.7-1 2.7-1.1 3.5-1.7 0.8-0.5 0.7-0.8 0.6-0.7 0.5-0.8 0.1-2.2-1.7-15.8 0.1-1.2 0.1-1.2 0.3-1 0.5-0.8 0.6-0.7 2.3-1.7 0.5-0.9 0.3-0.9 0.5-2.2 0.2-1.2-0.4-1.6-0.9-2-2.4-3.2-2.6-2.5-3.3-2-2.3-1.8-2.4-2.7-0.6-1.6-0.5-2.2-0.5-6.7 0-1.8 1.6-8.4 0-0.1 0-0.6 0.3-1.3 0.3-0.9 0.2-0.9 2.9-7.9 0.4-2.2 0.1-2.5-0.1-1.1-0.3-1.1-9.3-19.8-1-1.3-2.9-1.1-1.7-0.3-1.6 0.1-8.9 2.8-1.2 0.2-1.2 0-1.2-0.1-0.7-1.6-0.4-2.6 0.4-6.6-0.1-6.3-2.8-7.6 7.3-7.2 0.8-1.3 0.9-1.8 1.3-5.4 6.9-18.3z"
                                    id="SVSV" name="San Vicente">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="Usulután" data-unis="3"
                                    data-desc="UGB, UNIVO, Modular"
                                    d="M656.4 317.9l15.4-1.8 4.4 0.3 2.3 2.9 3.2 5 2.5 2.4 3.3 2.3 2.9 1 9.2 1.2 2.3 0.7 1.2 1 0 1.1-1.8 5-0.2 1.1 0 1.2 0.7 3.2 1.1 2.7 0.5 1.6 0.2 1.4-0.2 1-0.3 1-0.6 0.8-0.6 0.7-0.8 0.4-2.1 0.7-0.8 0.5-0.6 0.7-0.5 0.8-0.3 1-0.1 1.2 0 2.3 0.2 1.1 0.3 1 4.5 9.6 0.5 1.5 0 0.9-0.8 3 0 1.6 0.3 2 1.5 5.1 0.2 1.5-0.1 0.8-0.5 1-0.1 0.1-2 1.9-0.6 0.7-0.5 0.9-0.3 1-0.1 1.2 0.1 2.3-0.2 2.4-0.4 1-0.5 0.6-0.7 0-0.6-0.5-0.5-0.6-0.4-0.4-0.6-0.2-0.7 0.2-0.6 0.7-0.3 1-0.1 1.2 0.1 1.1 0.6 5.2 0.1 2.5 1.2 7.1 0.4 1.2 0.6 0.4 0.6 0.1 1.8-0.7 1.5 0.5 0.6 1 0.2 1.2-0.1 2.5 0.2 2.3 0.6 3 2 6.9 2 4.1 3.8 6.1 2.6 3.4 0.7 0.5 0.8 0.5 2.8 1.3 2 0.5 7.4 0.8 1.7 1 1.9 1.6 3.3 4 1.6 1.4 1.1 0.8 0.9-0.1 1.1 0.1 0.9 0.4 0.8 0.5 1.5 1.2 0.8 0.5 0.9 0.4 1.1 0.1 1 0 1.1-0.3 1.9-0.8 3.5-1.9 1.2-0.1 1.6 0.2 1.2 1.4 0.7 1.1 0.3 1.2 0.2 1.1 0 5.7 0.9 1.4 1.4 1.2 2.5 1.3 0.9 1.2 0.2 1.1-2.8 5.1-0.4 1.3-0.2 1.2 0.1 1.9 0.7 0.8 0.9 0.2 10.2-1.6 1.2 0.1 1 0.2 1 0.4 23 26.8-3.5 1.4-32.4 2.7-34.8-5.3-9 2.9-4.8-1.6-5.5-1.2 0-2.4 8.5-0.6 0.6-5.9-4.5-7.4-6.9-4.8 1.5 3 4.5 6.3 1.7 3.9-13.6-0.5-6.6 0.7-5.7 2.4 3.7 1.6 2.9-0.5 3.5-1 5.3-0.1 0 2.9-8.7 3.6-9.7-0.3-7.5-4.6-2.3-9.5 3.7 1.7 1.4 0.9 5.2-5.2 0-2.4-6.5-2.9-7-10-4.7-2.9 1.2 5.5 2.2 4.6 1.1 4.1-1.7 4-5.5-3.2-15.9-4-6.8-3.6-6.2-11.1-2.9-1.8-4.4-0.4-12.4-2.5-5.5 0-15.2 2.9-21.2 0.5-4.7 2.1 2.6 2 2.7 1.5 2.8 0.5 2.4-1.1 10.6 2.4 30.2-3.2 3.3 0.5 4.9 2.7 2.6 2.4 4.7 5.7 3.1 2.4 0 2.9-7-1.2-5.1-1-11.2-3.3 0 2.6 30.8 6.3 16.1 5.3 7.2 7.1-2.7 4.6-6.7-1.1-7.5-4-4.9-3.7-5.5-2.8-97-18.1-11.6-5.4-0.2-0.1 0-0.8 1.5-5.5 2.8-3.8 4.5-1.7 7.3-1.1 3.6-2.9 2.2-4.2 3.5-5.2 6.6-6.4 0.9-2.6 0.5-12 0.9-4.9 1.6-4.1 17.1-32.6 3.2-11.5 0.9-11.7 2.1-3 6.2-1.1 0.5-2.4 10.8-13.4 8.3 1.9 7.5-4.8 5.6-7.7 2.1-6.8 23.6-17.2z"
                                    id="SVUS" name="Usulután">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="Cuscatlán" data-unis="2"
                                    data-desc="UNICO, UNIVO sede Cuscatlán"
                                    d="M410.9 171.4l6.6-3.4 1.2 0 1.3 0.1 4.7 2.4 9.7 3.5 1.9 1.1 2.7 2.4 6.3 4.5 0.6 0.8 1.7 3 8.2 9.8 0.7 1 0.2 0.7 0 0.1-0.3 1.6 0 1.1 0.1 0.9 0.3 1 0.6 1.8 1 2.1 2 2.9 1.7 1.6 1.4 0.9 8 3.8 6.8-0.5 5.8 2.2 6.2 0.7-5.5 8.8-0.4 0.9-1.1 1.6-1.3 1.4-1.4 1.2-5 2.9-0.8 0.6-0.6 0.6-0.6 0.9-0.3 0.9-0.3 1-0.2 1.2 0.9 2.5 1.8 3.3 7.3 10.4 2.3 2.4 3.1 2.1 1.8 2.1 1.4 3 0.3 1.7 0.4 1.6 1.9 3.4 0.5 1.5 0.5 2.5 0.9 1.7 1.5 2 3.6 3.9 2.4 3.7 0.4 1 3.4 1.9 13 3.6-6.9 18.3-1.3 5.4-0.9 1.8-0.8 1.3-7.3 7.2-2.6 1.6-2.1 0.7-4.7 0.6-1 0.3-0.8 0.4-0.7 0.8-0.8 1.8-0.6 0.7-0.8 0.4-0.7 0.2-1.7 0.3-1.3 0-7.1-2.2-6.5-3.5-16.5-6.1-0.3-11.6-0.8-2-0.7-2-1.8-2.3-6.6-12.5-0.5-1.8 0.6-8.3-0.3-1.7-0.7-1.1-1-0.3-1.4-1-1.6-1.6-4.7-7-0.7-0.6-1-0.4-1.1-0.2-2.4-0.1-2.6-2.1-3.4-4-10-15.7-1.1-3 0.7-0.6 2.7-1.2 1-0.7 0.7-0.8 0.7-1.5-0.4-0.8-2.3-3-7.8-12.8-3.5-7.4-0.8-2.9-0.4-3.3 0.2-0.6 0.6-0.5 0.7-0.6 0.7-0.6 1-1.6 0.3-0.9 0.3-1.1 0.3-2.3 0.5-2.1 0.2-1.6 0-0.4 0.1-0.7-0.3-3.7-1.2-6.4-0.1-1.7 0.3-1.2 1.8-3.7 1.7-4.6 0.2-3.8z"
                                    id="SVCU" name="Cuscatlán">
                                </path>
                            </a>
                            <a>
                                <path class="dept cursor-pointer" data-name="San Salvador" data-unis="14"
                                    data-desc="UES, UCA, UJMD, UFG, Tecnológica, UTEC y más"
                                    d="M363.6 177.2l2.7 0.4 1.5-0.8 4.9-3.5 2.9-1 7.8 3.4 5.2 0.1 2.4-4.8 5.3 4.2 4.2 1 10.4-4.8-0.2 3.8-1.7 4.6-1.8 3.7-0.3 1.2 0.1 1.7 1.2 6.4 0.3 3.7-0.1 0.7 0 0.4-0.2 1.6-0.5 2.1-0.3 2.3-0.3 1.1-0.3 0.9-1 1.6-0.7 0.6-0.7 0.6-0.6 0.5-0.2 0.6 0.4 3.3 0.8 2.9 3.5 7.4 7.8 12.8 2.3 3 0.4 0.8-0.7 1.5-0.7 0.8-1 0.7-2.7 1.2-0.7 0.6 1.1 3 10 15.7 3.4 4 2.6 2.1 2.4 0.1 1.1 0.2 1 0.4 0.7 0.6 4.7 7 1.6 1.6 1.4 1 1 0.3 0.7 1.1 0.3 1.7-0.6 8.3 0.5 1.8 6.6 12.5 1.8 2.3 0.7 2 0.8 2 0.3 11.6-13.6 12.8-11.3 14.4-1.9 1.5-1.1-0.1-1.2-0.2-6-1.9-1.1-0.2-1.2 0-1 0.2-0.9 0.5-0.5 1.2-0.2 1.8 0.7 3.3 1.1 3.1 2.9 5.1 0.7 1.9-0.2 2.7-0.8 4.2-3.2 9.6-1.9 4.1-2.7 3-1.1 0.7-0.8 3.2-2.8-1.2-1-0.3-1.4-0.2-2.2 0.3-3.9 1.3-2.1 0.5-1.2 0.1-1.1-0.2-0.8-0.4-0.6-0.7-2.1-4.6-6-9.8-0.2-1.4 0.1-1.8 0.8-3.4 2.4-6.1 0.2-1.2-0.7-15.9 0.2-3.7 0.6-2.2 0.7-0.6 0.6-0.7 0.2-0.9-0.4-0.9-2.9-1.4-0.8-0.8-0.1-1.2 0.9-2.2 1.2-2.3 0.2-1.2-0.4-1.4-2.5-3.4-1.6-1.4-0.7-0.5-5.4-2.9-0.8-0.6-0.7-1.1-0.6-1.7-0.7-3-0.4-1.3-0.7-0.9-1.5-1.1-0.6-0.7-0.5-0.8-0.4-1-0.3-3.7 0.4-3.8-1.9-2.5-1.2-0.1-1-0.3-0.6-0.8-0.2-1.5 1.7-4.8 2.6-4.9 0.3-1 0.3-1.6 0.4-6.2 0.7-3.6 1.2-2.8 0.8-3 0.7-6.8 0.5-1.8 0.7-1.9 1.2-1.4 0.7-0.6 0.9-0.5 1.9-0.7 0.8-0.5 0.4-0.9 0.5-2.2 1.9-21.6 0.4-1.2 0.7-0.7 1-0.3 2.8 0.1 0.3-0.3 0.1-0.4 0.5-2.2 1-2.6-0.1-0.6-0.6-0.8-2-1.1-1.4-1.1-1.2-0.7-1-0.4-1.1-0.2-1.1-0.2-1.2 0-3.5 0.3-1.1-0.1-3-0.9-1.8-0.9-1.6-1-1-1.4-1.1-2.1-1.9-4.4-1.1-2-0.9-1.3-2.3-1.8-0.2-1.4 0.1-2.1 2-8.3 0.4-13z"
                                    id="SVSS" name="San Salvador">
                                </path>
                            </a>

                        </g>
                        <g id="points">
                            <circle class="13.222973684790423|-89.99369975031595" cx="90.9" cy="497.5"
                                id="0">
                            </circle>
                            <circle class="13.737667945092843|-88.78290699198428" cx="545.5" cy="298.8"
                                id="1">
                            </circle>
                            <circle class="14.381035770470868|-87.81427278531893" cx="909.1" cy="49.8"
                                id="2">
                            </circle>
                        </g>
                        <g id="label_points">
                            <circle class="Ahuachapán" cx="124.3" cy="249.3" id="SVAH">
                            </circle>
                            <circle class="Santa Ana" cx="251.3" cy="168.8" id="SVSA">
                            </circle>
                            <circle class="Chalatenango" cx="413.2" cy="117.2" id="SVCH">
                            </circle>
                            <circle class="Cabañas" cx="580.5" cy="233.6" id="SVCA">
                            </circle>
                            <circle class="Morazán" cx="808.4" cy="292.1" id="SVMO">
                            </circle>
                            <circle class="San Miguel" cx="753.2" cy="407.8" id="SVSM">
                            </circle>
                            <circle class="La Unión" cx="879.7" cy="380.1" id="SVUN">
                            </circle>
                            <circle class="Sonsonate" cx="208.7" cy="323.8" id="SVSO">
                            </circle>
                            <circle class="La Libertad" cx="325.4" cy="339.5" id="SVLI">
                            </circle>
                            <circle class="La Paz" cx="478.8" cy="400" id="SVPA">
                            </circle>
                            <circle class="San Vicente" cx="567.9" cy="326.5" id="SVSV">
                            </circle>
                            <circle class="Usulután" cx="635.8" cy="419.6" id="SVUS">
                            </circle>
                            <circle class="Cuscatlán" cx="446.2" cy="245.2" id="SVCU">
                            </circle>
                            <circle class="San Salvador" cx="406.6" cy="297.7" id="SVSS">
                            </circle>
                        </g>
                    </svg>
                </div>

                <div class="map-tooltip" id="mapTooltip">
                    <strong id="tooltipName">Departamento</strong>
                    <span class="tooltip-unis" id="tooltipUnis">— universidades</span>
                    <span class="tooltip-desc" id="tooltipDesc"></span>
                </div>
            </div>
        </div>



    </section>

    <!--- div del modal mapa --->

    <div id="uniModal" class="modal">

        <div class="modal-content">

            <span id="closeModal">&times;</span>

            <h2 id="modalDept"></h2>

            <div id="universidadesContainer"></div>

        </div>

    </div>

    </div>

    <!-- HISTORIA (abyss zone) -->

    <section class="section section-abyss" id="nosotros">
        <div class="container">
            <div class="historia-grid" data-reveal>
                <div class="historia-text">
                    <span class="section-tag">Nuestra Historia</span>
                    <h2>Nacimos de la misma<br />necesidad que tú sientes</h2>
                    <p class="historia-quote">
                        UGF nació de la mente de un estudiante de bachillerato que no podía costear la universidad. Hoy
                        es la plataforma que conecta el talento salvadoreño con oportunidades reales, sin importar el
                        origen económico.
                    </p>
                    <a href="#" class="btn-primary">Conocer la historia completa →</a>
                </div>
                <div class="mvv-cards">
                    <div class="mvv-card" data-reveal>
                        <div class="mvv-icon">
                            <svg viewBox="0 0 32 32" fill="none">
                                <path d="M16 4L4 12v16h24V12L16 4z" stroke="currentColor" stroke-width="1.5"
                                    stroke-linejoin="round" />
                                <rect x="12" y="20" width="8" height="12" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                        </div>
                        <h4>Misión</h4>
                        <p>Democratizar el acceso a la educación universitaria en El Salvador.</p>
                    </div>
                    <div class="mvv-card highlight" data-reveal>
                        <div class="mvv-icon">
                            <svg viewBox="0 0 32 32" fill="none">
                                <polygon points="16,2 20,12 31,12 22,19 25,30 16,23 7,30 10,19 1,12 12,12"
                                    stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h4>Visión</h4>
                        <p>Ser la red educativa más grande de Centroamérica para 2030.</p>
                    </div>
                    <div class="mvv-card" data-reveal>
                        <div class="mvv-icon">
                            <svg viewBox="0 0 32 32" fill="none">
                                <path d="M16 28s-12-7-12-16a8 8 0 0116 0 8 8 0 0116 0c0 9-12 16-12 16z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h4>Valores</h4>
                        <p>Transparencia, equidad y compromiso con cada estudiante.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA (treasure at the bottom) -->
    <section class="cta-section section-treasure">
        <div class="treasure-glow"></div>
        <div class="container cta-content" data-reveal>
            <p>Miles de estudiantes ya están navegando con UGF. ¿Que esperas?</p>
            <a href="{{ url('/becas') }}">Ver lista de Becas</a>
            Únete ahora
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none">
                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <a class="nav-logo" href="#"> UGF</a>
                <p>Plataforma de Becas El Salvador</p>
            </div>
            <div class="footer-links">
                <div>
                    <h5>Plataforma</h5>
                    <a href="#">Test Socioemocional</a>
                    <a href="#">Mapa de Universidades</a>
                    <a href="#">Calendario de Becas</a>
                </div>
                <div>
                    <h5>Comunidad</h5>
                    <a href="#">Red Académica</a>
                    <a href="#">Padrinos</a>
                    <a href="#">Testimonios</a>
                </div>
                <div>
                    <h5>Empresa</h5>
                    <a href="#">Sobre nosotros</a>
                    <a href="#">Contacto</a>
                    <a href="#">Privacidad</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 UGF. Hecho en El Salvador.</p>
        </div>


    </footer>

    <dialog id="departmentModal"
        class="hidden backdrop:bg-black/80 p-6 rounded-2xl max-w-2xl w-full bg-[#071f35] border border-blue-500/30 text-white flex-col outline-none fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[999]">
        <div class="flex justify-between items-center border-b border-blue-500/20 pb-4 mb-4">
            <h3 id="modalDeptName" class="text-2xl font-bold text-yellow-400"></h3>
            <button onclick="window.closeModal()"
                class="text-gray-400 hover:text-white text-2xl font-bold cursor-pointer"
                style="background:none; border:none; outline:none;">&times;</button>
        </div>

        <p id="modalDeptDesc" class="text-gray-300 mb-4"></p>

        <div class="overflow-y-auto max-h-[60vh] pr-2">
            <h4 class="text-lg font-semibold text-teal-400 mb-3">Universidades con Becas:</h4>
            <div id="modalUniversitiesList" class="space-y-6"></div>
        </div>
    </dialog>
</body>

</html>
