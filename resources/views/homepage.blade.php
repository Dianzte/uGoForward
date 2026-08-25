<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UGF  Navega hacia tu futuro</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  @vite(['resources/css/homepage.css', 'resources/js/homepage.js' ,'resources/css/app.css'])

</head>

<body>
 

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
    <div class="ship-speech-bubble">⛵ ¡Navega hacia tu futuro!</div>
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

  <!-- HERO -->
  <section class="hero section-panel" id="inicio">
    <div class="container hero-content">
      <div class="badge" data-reveal>
        <span class="badge-dot"></span>
        Plataforma de Becas Nacionales — El Salvador
      </div>
      <h1 data-reveal>
        Tu futuro <br />
        <em>No tiene límites</em>
      </h1>
      <br /><br />
      <p class="hero-sub" data-reveal>
        Conectamos estudiantes salvadoreños con oportunidades de beca universitaria,<br class="br-desktop" />
        padrinos comprometidos e instituciones educativas de todo El Salvador.
      </p>
      <br /><br />
      <div class="hero-cta" data-reveal>
        <a href="{{ route('becas.index') }}" class="btn-primary btn-lg">
          Comenzar ahora
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="#becas" class="btn-outline btn-lg">Explorar Becas</a>
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
            <svg viewBox="0 0 40 40" fill="none"><circle cx="20" cy="16" r="8" stroke="currentColor" stroke-width="2"/><path d="M8 32c0-5.523 5.373-10 12-10s12 4.477 12 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </div>
          <h3>Test Socioemocional</h3>
          <p>Un análisis profundo de tu perfil emocional e intelectual que te guía hacia la carrera ideal según tus fortalezas.</p>
          <a href="#" class="card-link">Hacer el test →</a>
        </div>

        <div class="service-card featured" data-reveal>
          <div class="service-icon" style="--icon-color:#e8c847;">
            <svg viewBox="0 0 40 40" fill="none"><path d="M12 20l4 4 12-12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="20" cy="20" r="14" stroke="currentColor" stroke-width="2"/></svg>
          </div>
          <h3>Sistema de Padrinos</h3>
          <p>Conecta con personas dispuestas a financiar tu educación bajo acuerdos claros y justos con condiciones personalizadas.</p>
          <a href="#" class="card-link">Conocer más →</a>
        </div>

        <div class="service-card" data-reveal>
          <div class="service-icon" style="--icon-color:#4fc3f7;">
            <svg viewBox="0 0 40 40" fill="none"><path d="M8 28L20 10l12 18H8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="20" cy="10" r="2" fill="currentColor"/></svg>
          </div>
          <h3>Mapa de Universidades</h3>
          <p>Explora todas las universidades de El Salvador que ofrecen becas, organizadas por departamento.</p>
          <a href="#universidades" class="card-link">Ver mapa →</a>
        </div>

        <div class="service-card" data-reveal>
          <div class="service-icon" style="--icon-color:#81c784;">
            <svg viewBox="0 0 40 40" fill="none"><rect x="8" y="8" width="24" height="28" rx="2" stroke="currentColor" stroke-width="2"/><path d="M14 16h12M14 22h8M14 28h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </div>
          <h3>Tests de Práctica</h3>
          <p>Prepárate con exámenes simulados por carrera y compara tus resultados con otros estudiantes.</p>
          <a href="#" class="card-link">Practicar →</a>
        </div>

        <div class="service-card" data-reveal>
          <div class="service-icon" style="--icon-color:#f48fb1;">
            <svg viewBox="0 0 40 40" fill="none"><rect x="6" y="8" width="28" height="26" rx="2" stroke="currentColor" stroke-width="2"/><path d="M14 8V6M26 8V6M6 18h28" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M12 25h4v4h-4z" fill="currentColor" opacity=".4"/></svg>
          </div>
          <h3>Calendario de Becas</h3>
          <p>Nunca pierdas una convocatoria. Ve las fechas de aplicación de cada universidad en tiempo real.</p>
          <a href="{{ route('becas.calendario') }}" class="card-link">Ver calendario →</a>
        </div>

        <div class="service-card" data-reveal>
          <div class="service-icon" style="--icon-color:#ce93d8;">
            <svg viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="14" stroke="currentColor" stroke-width="2"/><path d="M14 20c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="15" cy="24" r="2.5" stroke="currentColor" stroke-width="1.5"/><circle cx="25" cy="24" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
          </div>
          <h3>Red Social Académica</h3>
          <p>Comparte y analiza respuestas con otros estudiantes salvadoreños. Aprende en comunidad.</p>
          <a href="#hub-social" class="card-link">Explorar Hub →</a>
        </div>

      </div>
    </div>
  </section>

  <!-- CARRUSEL DE BECAS DESTACADAS (100% NACIONALES DE EL SALVADOR) -->
  <section class="becas-carousel-section" id="becas">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-tag">Becas Nacionales</span>
        <h2>Convocatorias Universitarias en El Salvador</h2>
        <p>Explora las becas ofrecidas por instituciones públicas y privadas de nuestro país.</p>
      </div>

      <div class="carousel-controls-wrapper" data-reveal>
        <div class="carousel-filter-pills" id="becasFilterPills">
          <button class="filter-pill active" data-filter="todos">Todas</button>
          <button class="filter-pill" data-filter="urgentes">⚡ Cierre Próximo</button>
          <button class="filter-pill" data-filter="pregrado">🎓 Pregrado / Licenciatura</button>
          <button class="filter-pill" data-filter="stem">⚙️ STEM e Ingeniería</button>
        </div>
        <div class="carousel-nav-btns">
          <button class="carousel-btn" id="prevBecaBtn" aria-label="Anterior">←</button>
          <button class="carousel-btn" id="nextBecaBtn" aria-label="Siguiente">→</button>
        </div>
      </div>

      <div class="carousel-track-container" data-reveal>
        <div class="carousel-track" id="becasTrack">
          
          <div class="beca-card-3d" data-category="urgentes pregrado stem">
            <div>
              <div class="beca-header-row">
                <span class="beca-tag beca-tag-urgente">Cierre Inminente</span>
                <span class="beca-days-badge">🔥 5 días restantes</span>
              </div>
              <h3 class="beca-card-title">Beca Excelencia UCA 2026</h3>
              <p class="beca-card-desc">Programa de apoyo económico integral para jóvenes sobresalientes en carreras de ingeniería y ciencias sociales.</p>
            </div>
            <div class="beca-card-footer">
              <span class="beca-univ-name">🏫 Universidad Centroamericana (UCA)</span>
              <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.4rem 0.9rem; font-size:0.8rem;">Ver Fecha →</a>
            </div>
          </div>

          <div class="beca-card-3d" data-category="pregrado stem">
            <div>
              <div class="beca-header-row">
                <span class="beca-tag beca-tag-pregrado">Pregrado / Tecnología</span>
                <span class="beca-days-badge">🟢 Convocatoria Abierta</span>
              </div>
              <h3 class="beca-card-title">Beca Talento UDB - Don Bosco</h3>
              <p class="beca-card-desc">Becas dirigidas a estudiantes de alto rendimiento académico en áreas STEM, Mecatrónica e Innovación Tecnológica.</p>
            </div>
            <div class="beca-card-footer">
              <span class="beca-univ-name">⚙️ Universidad Don Bosco (UDB)</span>
              <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.4rem 0.9rem; font-size:0.8rem;">Ver Fecha →</a>
            </div>
          </div>

          <div class="beca-card-3d" data-category="pregrado urgentes">
            <div>
              <div class="beca-header-row">
                <span class="beca-tag beca-tag-maestria">Universidad Nacional</span>
                <span class="beca-days-badge">⏳ 12 días restantes</span>
              </div>
              <h3 class="beca-card-title">Beca Remunerada UES 2026</h3>
              <p class="beca-card-desc">Exoneración de escolaridad y estipendio de apoyo mensual para estudiantes con necesidad económica de la UES.</p>
            </div>
            <div class="beca-card-footer">
              <span class="beca-univ-name">🏛️ Universidad de El Salvador (UES)</span>
              <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.4rem 0.9rem; font-size:0.8rem;">Ver Fecha →</a>
            </div>
          </div>

          <div class="beca-card-3d" data-category="pregrado">
            <div>
              <div class="beca-header-row">
                <span class="beca-tag beca-tag-pregrado">Pregrado Oriental</span>
                <span class="beca-days-badge">🗓️ Cierra en Agosto</span>
              </div>
              <h3 class="beca-card-title">Beca Liderazgo UGB - Oriente</h3>
              <p class="beca-card-desc">Becas académicas para bachilleres destacados en San Miguel, Usulután, Morazán y La Unión.</p>
            </div>
            <div class="beca-card-footer">
              <span class="beca-univ-name">📜 Univ. Gerardo Barrios (UGB)</span>
              <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.4rem 0.9rem; font-size:0.8rem;">Ver Fecha →</a>
            </div>
          </div>

          <div class="beca-card-3d" data-category="pregrado stem">
            <div>
              <div class="beca-header-row">
                <span class="beca-tag beca-tag-pregrado">Licenciatura / Salud</span>
                <span class="beca-days-badge">🟢 Abierta</span>
              </div>
              <h3 class="beca-card-title">Beca Mérito UNASA - Santa Ana</h3>
              <p class="beca-card-desc">Financiamiento parcial y total para estudiantes de la zona occidental en carreras de medicina y laboratorio.</p>
            </div>
            <div class="beca-card-footer">
              <span class="beca-univ-name">🏥 Univ. Autónoma de Santa Ana</span>
              <a href="{{ route('becas.calendario') }}" class="btn-primary" style="padding:0.4rem 0.9rem; font-size:0.8rem;">Ver Fecha →</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ANIMACIÓN Y PRESENTACIÓN: HUB SOCIAL DE ESTUDIANTES UGF (EN DESARROLLO POR EL EQUIPO) -->
  <section class="hub-social-section" id="hub-social">
    <div class="hub-social-banner"></div>
    <div class="container relative z-10">
      
      <div class="hub-social-grid">
        <!-- Texto explicativo -->
        <div data-reveal>
         
          <h2 style="font-size:clamp(2rem, 4vw, 3rem); font-family:'Syne', sans-serif; color:#fff; line-height:1.15;">
            El Punto de Encuentro <br />
            <em style="color:var(--teal); font-style:normal;">de los Estudiantes Salvadoreños</em>
          </h2>
          <p style="color:var(--text-2); margin-top:1.25rem; font-size:1rem; line-height:1.6;">
            Estamos construyendo un <strong>Hub Social exclusivo</strong> donde estudiantes de todos los departamentos de El Salvador podrán conectar, formar grupos de estudio, resolver guías académicas juntas y compartir consejos de postulación a becas.
          </p>

          <div style="display:flex; gap:1.5rem; margin-top:2rem; flex-wrap:wrap;">
            <div style="background:rgba(14,58,92,0.6); padding:1rem 1.25rem; border-radius:16px; border:1px solid rgba(100,200,255,0.15); flex:1; min-width:140px;">
              <span style="font-size:1.5rem;">💬</span>
              <h4 style="color:#fff; margin-top:0.4rem; font-size:0.95rem;">Salas por Carrera</h4>
              <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.2rem;">Debates y resolución de guías comunitarias.</p>
            </div>
            <div style="background:rgba(14,58,92,0.6); padding:1rem 1.25rem; border-radius:16px; border:1px solid rgba(100,200,255,0.15); flex:1; min-width:140px;">
              <span style="font-size:1.5rem;">🏆</span>
              <h4 style="color:#fff; margin-top:0.4rem; font-size:0.95rem;">Muro de Logros</h4>
              <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.2rem;">Celebrando a cada nuevo becado del país.</p>
            </div>
          </div>
        </div>

        <!-- Mockup animado con interacciones en tiempo real -->
        <div class="hub-feed-mockup" data-reveal>
          <div class="feed-header-bar">
            <span class="feed-live-indicator">
              <span class="feed-live-dot"></span>
              <span>Hub Social UGF — (Vista Previa)</span>
            </span>
          </div>

          <div class="social-post-card">
            <div class="social-post-user">
              <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="user-avatar-mini" alt="User">
              <div>
                <strong style="color:#fff; font-size:0.85rem; block;">Sofía Martínez</strong>
                <span style="font-size:0.72rem; color:var(--teal);">Aspirante a Ing. Química — UCA</span>
              </div>
            </div>
            <p class="social-post-text">"¿Alguien más aplicando a la Beca Excelencia UCA 2026? Organicemos un grupo de estudio virtual este fin de semana. 📚✨"</p>
          </div>

          <div class="social-post-card">
            <div class="social-post-user">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" class="user-avatar-mini" alt="User">
              <div>
                <strong style="color:#fff; font-size:0.85rem; block;">Mateo Hernández</strong>
                <span style="font-size:0.72rem; color:var(--gold);">Estudiante UES — San Miguel</span>
              </div>
            </div>
            <p class="social-post-text">"¡Les confirmo que ya abrieron la recepción de documentos para las becas en la sede oriental de la UES! Revisen el calendario. 🎉"</p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- MAPA DE EL SALVADOR -->
  <section class="section section-deep" id="universidades">
    <div class="container">
      <div class="section-header light" data-reveal>
        <span class="section-tag">Cobertura Nacional</span>
        <h2>Presencia en todo<br />El Salvador</h2>
        <p>Haz clic en cualquier departamento para ver las universidades disponibles con becas activas, horarios, carreras y servicios.</p>
      </div>

      <div class="map-wrapper" data-reveal>
        <!-- Hover Tooltip -->
        <div class="map-tooltip" id="mapTooltip">
          <strong id="tooltipName">Departamento</strong>
          <span class="tooltip-unis" id="tooltipUnis">
            <span>🎓</span><span id="tooltipUnisText">— universidades</span>
          </span>
          <span class="tooltip-click-hint">👆 Haz clic para ver detalles</span>
        </div>

        <!-- SVG Map -->
        <div class="container-mapa">
         <svg viewbox="0 0 1000 547" width="1000" xmlns="http://www.w3.org/2000/svg" id="svganimado">
 <g id="features">
    <path class="dept cursor-pointer" data-name="Ahuachapán" data-unis="2" data-id="ahuachaapan" d="M183.1 180l3.2 1.3 6.8 4.3 1.3 1.2 0.7 0.8 0.4 0.8 0.8 3.2 0.4 0.9 0.6 0.9 1.3 1.3 1.6 1.2 1.3 1.4 0.1 0.1 0.8 1.5 0.3 0.9-1.1 6.9-7.5 25.9-6.7 2.8-8.2 2.3-4.6 0.7-1 0.7-0.8 1.1-0.4 2.5-0.1 1.6 0.9 7.2 0.6 2.3 0.7 1.7 0.9 1.8 0.3 0.9-0.2 1.3-7.6 11.7-0.7 2.1-0.2 1.6 3.1 10 0.4 2.2-0.5 1.5-1.1 1.8-2.8 3.4-1.1 2.1-0.6 1.6-0.3 3.6-0.3 1-4.8 8.6-0.7 1.9-2.1 8.6-1.9 0.8-3.1 0.4-11.3-0.7-2.3-0.5-0.8-0.5-2.7-2.6-4.9-5.7-0.8-0.6-1.5-1.1-0.9-0.4-1-0.4-1.4-0.1-1.5 0-2.1 0.4-1.3 0.4-1.1 0.5-0.9 0.6-1.1 0.9-2.8 3.4-5.9 9.4-0.9 1.4-25.4-11.6-29-14-6-25.1-0.1-6 0.9-6.3 2-6.1 3-5.6 4.5-4.6 10.3-5.5 4.6-3.8 3.4-5.4 2.5-5.6 3.2-5.5 5.6-4.9 36.1-25.3 7.9-8 4-2.7 16.7-6.3 5.5-0.4 6.8 1.8 9.8 7.6 5.5 2.3 5.5-3 0.1-2.2-0.3-0.6z" id="SVAH" name="Ahuachapán"></path>
    <path class="dept cursor-pointer" data-name="Santa Ana" data-unis="4" data-id="santa-ana" d="M194.1 232.6l7.5-25.9 1.1-6.9-0.3-0.9-0.8-1.5-0.1-0.1-1.3-1.4-1.6-1.2-1.3-1.3-0.6-0.9-0.4-0.9-0.8-3.2-0.4-0.8-0.7-0.8-1.3-1.2-6.8-4.3-3.2-1.3-2.4-5.6-0.3-3 1-3.2 16.1-28.4 7.5-8.2 9.4-7.3 9.9-4.6 40.3-9.7 2.5-2.4-0.1-6-1.2-1.6-4.8-2.4-1.6-1.6-0.7-2.5-0.7-5.9-0.5-1.8-2.2-4.2-1-1.5-2.2-2-2.6-1.2-7.9-1-2.3-5.5 2.2-3.3 3.8-2.7 2.4-3.8-1.1-4.5-2.7-3.8-1.7-4.4 2.5-6.1 5.3-4 5.5 0.5 4.1 4.1 1.1 7.2 5.1-3.7 9.3-11.2 2.6-1.1 4.1-1.8 5.9 0.8 4.3 2.3 4.2 1.5 5.9-1.2 8.6-8.8 3.8-1.5 0.5 7.5 1.9-3 0.4-0.9 10.8 7.9 0.1 0.1 5.8 0.9 14.3 13.4 2.5 2.8 0.5 0.7 1.2 2.9 1.7 2.9 0.8 0.8 1.3 1.1 5.6 2.9 0.8 0.7 1.8 2.4 6.9 4.4-4.8 14-1.5 7.8-3.3 3.7-14 5.7 0 7.7 0.3 4.3-0.1 1.5-0.7 1.8-7.3 10.1-1.3 1.3-0.8 0.6-0.8 0.5-1 0.3-1 0.3-1.2 0.2-3.8 0.2-1 0.2-1 0.3-0.9 0.4-0.8 0.5-0.7 0.6-4.4 8.1-0.9 1.2-0.7 0.7-0.9 0.5-0.9 0.4-7.1-0.5-7.2 24.8 1.1 8.4 4.2 3.8 5.6 1.1 5.6 0 4.4-1.2-4 21.4-0.7 5.5 0.1 1.2-0.6 7.5-0.4 1.2-0.8 1-1.9 0.9-1.4 0-1.3 0-1 0.1-0.8 0.4-3 4.9-1.2 0.8-1.1 0.1-0.9-0.3-1-0.1-0.9 0.3-0.8 0.5-0.6 0.8-0.5 2.1-0.5 10.4 0.4 6.9-0.7 8.3-6.6 18-1.8 10.7-17.9-3.4-4.7-1.6-0.6-0.8-0.6-0.8-2.2-5.6-5-4.7-8-5.9-3.6-2.2-2.5-1.1-1 0.2-0.8 0.6-0.6 0.7-1 1.6-1.2 2.8-0.5 2.1 0 3.5-0.3 0.9-0.8 0.9-1.4 0.7-2.7 0.6-1.7-0.4-1.2-0.4-0.9-0.7-0.6-0.7-0.5-0.9-0.4-1.2-0.9-1.3-1.7-1.8-2.4-0.4-1.2-0.8-0.5-0.8 0.2-1 0.4-0.8 0.7-0.8 1.4-1.2 0.6-0.6 0.5-0.9 0.3-1.1 0.1-1-0.4-2-1.7-2.8-2.3-2.1-1.2-1.3-0.8-1.1-1.2-2.8-1.1-1.5-6.1-6-2.2-1.6-1.7-0.8-1.2 0-1.1 0.1-2.1 0.6-3.4 0.1-8.7-2.2z" id="SVSA" name="Santa Ana"></path>
    <path class="dept cursor-pointer" data-name="Chalatenango" data-unis="1" data-id="chalatenango" d="M324.5 181.7l-4.4 1.2-5.6 0-5.6-1.1-4.2-3.8-1.1-8.4 7.2-24.8 7.1 0.5 0.9-0.4 0.9-0.5 0.7-0.7 0.9-1.2 4.4-8.1 0.7-0.6 0.8-0.5 0.9-0.4 1-0.3 1-0.2 3.8-0.2 1.2-0.2 1-0.3 1-0.3 0.8-0.5 0.8-0.6 1.3-1.3 7.3-10.1 0.7-1.8 0.1-1.5-0.3-4.3 0-7.7 14-5.7 3.3-3.7 1.5-7.8 4.8-14-6.9-4.4-1.8-2.4-0.8-0.7-5.6-2.9-1.3-1.1-0.8-0.8-1.7-2.9-1.2-2.9-0.5-0.7-2.5-2.8-14.3-13.4 14.5 2.3 11.6 5.6 19.2 4.1 9.5 6.7 17.3 5.2 3.6-3.2 5.4-10.5 3-3.1 3.3-0.9 2.2 0.4 1.9 1.7 2.1 3 1.9 3.5 0.8 3.9-0.3 3.9-1.5 3.8 4.3 3.7 12.8 1.5 6 3 2.4 4.8 1.5 11.9 1.7 3.5 9.5 7.2 3.4 4.2 3.4 5.7 1.4 4.9 0.2 1.8 0.4 3.2 1.2 4.1 3.3 2.4 3.7-0.8 4.4-2.6 5.3-2.3 5.8 0.1 9.3 6 7.9 9.9 5.6 11.6 2.5 10.9 6.6-1 16.4 1.3 4.5-1.8 3.9-2.4 3.3-0.3 2.7 4.4-0.4 6.5-2.2 6.4-0.2 5.5 5.9 3.8 8 1.1 1.8 0.6 2.7 2.4 1.8 2.6 0.9 3.2-0.2 2-17.3 2.6-7.7 3.8-2.9 3.8-2.5 2-4.5 2.1-23.1 7.6-5.6 2.1-5.2 1.3-9.8-0.9-11.1 0.3-6.2-0.7-5.8-2.2-6.8 0.5-8-3.8-1.4-0.9-1.7-1.6-2-2.9-1-2.1-0.6-1.8-0.3-1-0.1-0.9 0-1.1 0.3-1.6 0-0.1-0.2-0.7-0.7-1-8.2-9.8-1.7-3-0.6-0.8-6.3-4.5-2.7-2.4-1.9-1.1-9.7-3.5-4.7-2.4-1.3-0.1-1.2 0-6.6 3.4-10.4 4.8-4.2-1-5.3-4.2-2.4 4.8-5.2-0.1-7.8-3.4-2.9 1-4.9 3.5-1.5 0.8-2.7-0.4-3.6-1.7-1.2-0.3-6.1-0.9-6.8-3.9-3.9-0.8-3.6 2.8-3.5 1.9-4.4 0.9-3.1 1.2-1.2 2.6-1.7 2.7z" id="SVCH" name="Chalatenango"></path>
    <path class="dept cursor-pointer" data-name="Cabañas" data-unis="1" data-id="cabanas" d="M490.3 217.5l11.1-0.3 9.8 0.9 5.2-1.3 5.6-2.1 23.1-7.6 4.5-2.1 2.5-2 2.9-3.8 7.7-3.8 17.3-2.6 0 0.7 2.3-0.9 9.5-1.5 9.6 0.7 28.4 9.1 16.6 1.2 3.6 1.6 3.4 3.3-0.3 1.2-0.1 0.9-0.2 0.2-0.4 0.3-0.1 0-1.5-0.2 0.4 0.9 1.9 4.2-0.2 4.5-1.3 4.7-0.8 5.7 1 3.7 4.1 10.2 0.3 6.1-3.3 5.5-0.7 0.6-1.7 1.5 2.4 4.8-0.6 4.6-2.6 3.9-4.1 2.6 0.8 5.3-3.5 5.4-3.4 6.7 0 0.1-6.1 4.1-3.5-0.9-9.5-1.2-1.4-0.5-0.7-0.6-0.5-0.8-0.3-1-0.4-2.1-0.6-0.9-1-0.5-2.6-0.7-0.9-0.4-2.6-1.5-3.9-1.4-0.9-0.5-6.9-5.1-0.9-0.4-4.1-0.8-5.2-0.3-2.4-0.4-12 0.1-2.7-0.2-1.8-0.5-5.4-3.9-1.2 0.1-1.4 0.7-1.9 2.5-1.2 1.3-1.3 0.8-2.2-0.2-2.7-0.6-2.5 0.6-11.7 5.2-3.6 1.2-2.6 0.4-3.9-1.3-2.2-0.5-2.4-0.1-1 1.4-0.7 1.2-0.5 11.1-13-3.6-3.4-1.9-0.4-1-2.4-3.7-3.6-3.9-1.5-2-0.9-1.7-0.5-2.5-0.5-1.5-1.9-3.4-0.4-1.6-0.3-1.7-1.4-3-1.8-2.1-3.1-2.1-2.3-2.4-7.3-10.4-1.8-3.3-0.9-2.5 0.2-1.2 0.3-1 0.3-0.9 0.6-0.9 0.6-0.6 0.8-0.6 5-2.9 1.4-1.2 1.3-1.4 1.1-1.6 0.4-0.9 5.5-8.8z" id="SVCA" name="Cabañas"></path>
    <path class="dept cursor-pointer" data-name="Morazán" data-unis="2" data-id="morazan" d="M736.4 228.6l1.5-2.6 2.5-2.8 4.5-1.9 3.3 0.5 2.4-0.5 1.8-5.3-0.6-1-3.4-3.1-0.8-1.4-0.3-7.7 0.1-1.3 3.7-1.4 5.7 0.6 0.9 0.2 9 2 6 0.2 19.6-2.4 9.9 0.6 4.3 3.6 5.5 14.3 5.4 6.6 7 6.3 6.1 7.4 3.1 9.5 17.1-8.5 5.6-1.5 0.9 0.1 1.2 7.6 1.6 6.3 0.7 10.9-1.5 23.5 0.1 1.3 0.2 1 0.5 0.8 1.7 2.2 0.5 0.9 0.4 0.9 0.3 0.9 0.4 2.4-0.2 7.4-1.1 8.9-2.2 10.4-8.6 22.3-0.7 7.3-2.7 4.2-11.6 12.4-8.1-1.3-3.9 0.2-14.7 4.6-2.8 0.3-1.8-0.3-0.6-0.6-0.6-1.1-0.3-0.8-0.9-1-1.3-1.2-5.5-3.3-0.8-0.6-0.6-0.8-0.4-0.8-1.1-6.9-0.6-2.1-1.4-2.6-1.6-2.1-1.3-0.8-1.4-0.5-14.6 1.2-2.1 0.4-1.9 0.8-3 1.8-2.1 0.6-0.6-0.5-0.1-0.6 0.5-0.9 2.2-3 0.4-0.9 0.1-1-0.6-1-0.9-0.9-2.7-1.7-0.8-0.7-0.6-0.7-0.3-0.9-0.3-1.1-0.2-1.1-0.8-1.5-1.4-1.9-3.5-2.6-1.4-1.4-0.7-2.3-1.2-1.9-4.8-4.3-1.9-2.3-1.2-1.8-0.6-9.7 0-1 0.4-0.8 0.7-0.7 12.3-8.9 1.7-1 2.9-1 0.9-0.5 0.7-0.7 0.6-0.7 0.3-1.1 0-1.4-0.7-2.3-1-1.5-0.8-1-14.2-9.7-2.1-1.9-1.4-1.5-0.3-1-0.3-1-0.1-3.8 0.3-2.1 0.2-0.7 0.2-0.7 0.5-0.8 1.1-1.4 0.7-0.6 0.8-0.5 0.6-0.7 0.6-0.8 0.4-0.8 0.4-1 0.2-1.1 0-2.3-1.2-3.2-1.5-2.9-0.6-1.7-0.1-1.3-0.7-3.1-4.4-10.4-2.1-2.9z" id="SVMO" name="Morazán"></path>
    <path class="dept cursor-pointer" data-name="San Miguel" data-unis="5" data-id="san-miguel" d="M639.5 290.4l0-0.1 3.4-6.7 3.5-5.4-0.8-5.3 4.1-2.6 2.6-3.9 0.6-4.6-2.4-4.8 1.7-1.5 0.7-0.6 10-0.5 7-4.3 6.8-2.9 15.2-3.3 3.5 0.2 6 2.3 2.8 0.3 0.4-1.1 0-2.3 0.3-2.4 1.2-1.4 2.4-0.1 3 0.5 3 0.9 2.4 1 2.6-4.8 4.1-1.5 4.5-0.4 4.1-1.4 3-2.8 1.2-2.3 2.1 2.9 4.4 10.4 0.7 3.1 0.1 1.3 0.6 1.7 1.5 2.9 1.2 3.2 0 2.3-0.2 1.1-0.4 1-0.4 0.8-0.6 0.8-0.6 0.7-0.8 0.5-0.7 0.6-1.1 1.4-0.5 0.8-0.2 0.7-0.2 0.7-0.3 2.1 0.1 3.8 0.3 1 0.3 1 1.4 1.5 2.1 1.9 14.2 9.7 0.8 1 1 1.5 0.7 2.3 0 1.4-0.3 1.1-0.6 0.7-0.7 0.7-0.9 0.5-2.9 1-1.7 1-12.3 8.9-0.7 0.7-0.4 0.8 0 1 0.6 9.7 1.2 1.8 1.9 2.3 4.8 4.3 1.2 1.9 0.7 2.3 1.4 1.4 3.5 2.6 1.4 1.9 0.8 1.5 0.2 1.1 0.3 1.1 0.3 0.9 0.6 0.7 0.8 0.7 2.7 1.7 0.9 0.9 0.6 1-0.1 1-0.4 0.9-2.2 3-0.5 0.9 0.1 0.6 0.6 0.5 2.1-0.6 3-1.8 1.9-0.8 2.1-0.4 14.6-1.2 1.4 0.5 1.3 0.8 1.6 2.1 1.4 2.6 0.6 2.1 1.1 6.9 0.4 0.8 0.6 0.8 0.8 0.6 5.5 3.3 1.3 1.2 0.9 1 0.3 0.8 0.6 1.1 0.6 0.6 1.8 0.3 2.8-0.3 14.7-4.6 3.9-0.2 8.1 1.3-2.5 3.3-0.8 4.9 0.2 11.7 1.5 5.6 0 1.2-0.4 1.3-1.4 1.7-1.1 0.9-1 0.8-1.6 0.9-0.7 0.6-0.6 1.5-0.3 2.5 0.2 9.9 1.1 6.8 0.3 1 0.5 0.9 3.5 5.6 0.4 0.9 0.4 1 0.1 0.9-4.8 27.8 0.1 1.6 0.6 2.2-0.3 2.4-4.9 16.1-0.8 0.9-1.8 0.5-1.2 0-1.3-0.3-3.9-1.4-3.2-1.9-3.1-2.3-0.8-0.3-1 3.8-0.5 2.9-2.1 30.2 0 0.1-2.6-0.5-2.5 0-2.8 1.1-23-26.8-1-0.4-1-0.2-1.2-0.1-10.2 1.6-0.9-0.2-0.7-0.8-0.1-1.9 0.2-1.2 0.4-1.3 2.8-5.1-0.2-1.1-0.9-1.2-2.5-1.3-1.4-1.2-0.9-1.4 0-5.7-0.2-1.1-0.3-1.2-0.7-1.1-1.2-1.4-1.6-0.2-1.2 0.1-3.5 1.9-1.9 0.8-1.1 0.3-1 0-1.1-0.1-0.9-0.4-0.8-0.5-1.5-1.2-0.8-0.5-0.9-0.4-1.1-0.1-0.9 0.1-1.1-0.8-1.6-1.4-3.3-4-1.9-1.6-1.7-1-7.4-0.8-2-0.5-2.8-1.3-0.8-0.5-0.7-0.5-2.6-3.4-3.8-6.1-2-4.1-2-6.9-0.6-3-0.2-2.3 0.1-2.5-0.2-1.2-0.6-1-1.5-0.5-1.8 0.7-0.6-0.1-0.6-0.4-0.4-1.2-1.2-7.1-0.1-2.5-0.6-5.2-0.1-1.1 0.1-1.2 0.3-1 0.6-0.7 0.7-0.2 0.6 0.2 0.4 0.4 0.5 0.6 0.6 0.5 0.7 0 0.5-0.6 0.4-1 0.2-2.4-0.1-2.3 0.1-1.2 0.3-1 0.5-0.9 0.6-0.7 2-1.9 0.1-0.1 0.5-1 0.1-0.8-0.2-1.5-1.5-5.1-0.3-2 0-1.6 0.8-3 0-0.9-0.5-1.5-4.5-9.6-0.3-1-0.2-1.1 0-2.3 0.1-1.2 0.3-1 0.5-0.8 0.6-0.7 0.8-0.5 2.1-0.7 0.8-0.4 0.6-0.7 0.6-0.8 0.3-1 0.2-1-0.2-1.4-0.5-1.6-1.1-2.7-0.7-3.2 0-1.2 0.2-1.1 1.8-5 0-1.1-1.2-1-2.3-0.7-9.2-1.2-2.9-1-3.3-2.3-2.5-2.4-3.2-5-2.3-2.9-4.4-0.3-15.4 1.8-4.3-6.8-11.6-11.6-1-9.1z" id="SVSM" name="San Miguel"></path>
    <path class="dept cursor-pointer" data-name="La Unión" data-unis="1" data-id="la-union" d="M836.2 370.7l11.6-12.4 2.7-4.2 0.7-7.3 8.6-22.3 2.2-10.4 1.1-8.9 0.2-7.4-0.4-2.4-0.3-0.9-0.4-0.9-0.5-0.9-1.7-2.2-0.5-0.8-0.2-1-0.1-1.3 1.5-23.5-0.7-10.9-1.6-6.3-1.2-7.6 2.1 0.5 5.5 3.6 2.6 0.7 1.7-0.8 4.8-3.8 3-1 3.7 0.2 7.4 1.6 3.6-0.3 3.6-2.6 3.1-3.9 3.8-3.2 5.6-0.2 3.6 2.8 3.6 10.2 3.1 4.6 1.9 1.1 5.3 2.1 2.3 1.2 5.9 5.2 10.6 9.1 6.6 2.7-3.3 5.6-7.6 17.6-1.9 6.3 2.4 6.4-1.8 4.1-3.8 3.6-3.3 4.7-1.3 6.9 0.3 14.3-0.9 7-9 21.9-2.2 10.6 4 8.8 3.8 1.4 8-2.6 5.4 2.1 2.9 3.4 2 4.6 0.6 5.2-0.9 4.9-6.4 7.4-29.6 13.6-0.3-0.1-2.9-1.8-1.9-3.1-2.8-8.3-10.6 18.4-2.7 10.8 6.9 4.8 4.4 1.7 12.4 12 7.1 4.8 0.6 2.2 0 4.7-3 8.2-7.5 5.9-27.9 14.1-7.6 6.1-1.3 6.1 8.7 6-13.3 3.1-51.7-3.6-9.6-1.8 0-0.1 2.1-30.2 0.5-2.9 1-3.8 0.8 0.3 3.1 2.3 3.2 1.9 3.9 1.4 1.3 0.3 1.2 0 1.8-0.5 0.8-0.9 4.9-16.1 0.3-2.4-0.6-2.2-0.1-1.6 4.8-27.8-0.1-0.9-0.4-1-0.4-0.9-3.5-5.6-0.5-0.9-0.3-1-1.1-6.8-0.2-9.9 0.3-2.5 0.6-1.5 0.7-0.6 1.6-0.9 1-0.8 1.1-0.9 1.4-1.7 0.4-1.3 0-1.2-1.5-5.6-0.2-11.7 0.8-4.9 2.5-3.3z" id="SVUN" name="La Unión"></path>
    <path class="dept cursor-pointer" data-name="Sonsonate" data-unis="3" data-id="sonsonate" d="M106 326.8l0.9-1.4 5.9-9.4 2.8-3.4 1.1-0.9 0.9-0.6 1.1-0.5 1.3-0.4 2.1-0.4 1.5 0 1.4 0.1 1 0.4 0.9 0.4 1.5 1.1 0.8 0.6 4.9 5.7 2.7 2.6 0.8 0.5 2.3 0.5 11.3 0.7 3.1-0.4 1.9-0.8 2.1-8.6 0.7-1.9 4.8-8.6 0.3-1 0.3-3.6 0.6-1.6 1.1-2.1 2.8-3.4 1.1-1.8 0.5-1.5-0.4-2.2-3.1-10 0.2-1.6 0.7-2.1 7.6-11.7 0.2-1.3-0.3-0.9-0.9-1.8-0.7-1.7-0.6-2.3-0.9-7.2 0.1-1.6 0.4-2.5 0.8-1.1 1-0.7 4.6-0.7 8.2-2.3 6.7-2.8 8.7 2.2 3.4-0.1 2.1-0.6 1.1-0.1 1.2 0 1.7 0.8 2.2 1.6 6.1 6 1.1 1.5 1.2 2.8 0.8 1.1 1.2 1.3 2.3 2.1 1.7 2.8 0.4 2-0.1 1-0.3 1.1-0.5 0.9-0.6 0.6-1.4 1.2-0.7 0.8-0.4 0.8-0.2 1 0.5 0.8 1.2 0.8 2.4 0.4 1.7 1.8 0.9 1.3 0.4 1.2 0.5 0.9 0.6 0.7 0.9 0.7 1.2 0.4 1.7 0.4 2.7-0.6 1.4-0.7 0.8-0.9 0.3-0.9 0-3.5 0.5-2.1 1.2-2.8 1-1.6 0.6-0.7 0.8-0.6 1-0.2 2.5 1.1 3.6 2.2 8 5.9 5 4.7 2.2 5.6 0.6 0.8 0.6 0.8 4.7 1.6 17.9 3.4 4.3 1.4 0.9 0.4 0.8 0.6 0.7 0.6 0.5 0.8 0.3 1 0.2 1-0.5 1.5-1 1.5-2.7 2.4-2.4 1.7-5.5 3.2-2.1 1.9-4.4 5.5-1.5 1.2-1.5 0.6-2.6 0.3-0.9 0.2-1.5 0.9-0.8 0.7-0.8 1.4-0.8 2.1-1.1 4.6-0.6 4.5 0.5 8.5-0.1 1.3-1 1.8-1.9 2.6-8.2 8.2-0.7 0.5-0.8 0.8-0.9 1.2-0.8 1.9-0.7 3.2-0.5 4.7-1.1 1.5-1.6 1.9-9.5 8.4-2 3.2-4 9.1-1 2.5-2.6-0.6-23.8-7.8-12.6 2.6-35.6 0.4-5.7-3.9-3.8-14.2-1.4-8.9-4.7-3.9-7.8-4.8-30.4-17.3-0.8-0.4z" id="SVSO" name="Sonsonate"></path>
    <path class="dept cursor-pointer" data-name="La Libertad" data-unis="6" data-id="la-libertad" d="M292.5 284.3l1.8-10.7 6.6-18 0.7-8.3-0.4-6.9 0.5-10.4 0.5-2.1 0.6-0.8 0.8-0.5 0.9-0.3 1 0.1 0.9 0.3 1.1-0.1 1.2-0.8 3-4.9 0.8-0.4 1-0.1 1.3 0 1.4 0 1.9-0.9 0.8-1 0.4-1.2 0.6-7.5-0.1-1.2 0.7-5.5 4-21.4 1.7-2.7 1.2-2.6 3.1-1.2 4.4-0.9 3.5-1.9 3.6-2.8 3.9 0.8 6.8 3.9 6.1 0.9 1.2 0.3 3.6 1.7-0.4 13-2 8.3-0.1 2.1 0.2 1.4 2.3 1.8 0.9 1.3 1.1 2 1.9 4.4 1.1 2.1 1 1.4 1.6 1 1.8 0.9 3 0.9 1.1 0.1 3.5-0.3 1.2 0 1.1 0.2 1.1 0.2 1 0.4 1.2 0.7 1.4 1.1 2 1.1 0.6 0.8 0.1 0.6-1 2.6-0.5 2.2-0.1 0.4-0.3 0.3-2.8-0.1-1 0.3-0.7 0.7-0.4 1.2-1.9 21.6-0.5 2.2-0.4 0.9-0.8 0.5-1.9 0.7-0.9 0.5-0.7 0.6-1.2 1.4-0.7 1.9-0.5 1.8-0.7 6.8-0.8 3-1.2 2.8-0.7 3.6-0.4 6.2-0.3 1.6-0.3 1-2.6 4.9-1.7 4.8 0.2 1.5 0.6 0.8 1 0.3 1.2 0.1 1.9 2.5-0.4 3.8 0.3 3.7 0.4 1 0.5 0.8 0.6 0.7 1.5 1.1 0.7 0.9 0.4 1.3 0.7 3 0.6 1.7 0.7 1.1 0.8 0.6 5.4 2.9 0.7 0.5 1.6 1.4 2.5 3.4 0.4 1.4-0.2 1.2-1.2 2.3-0.9 2.2 0.1 1.2 0.8 0.8 2.9 1.4 0.4 0.9-0.2 0.9-0.6 0.7-0.7 0.6-0.6 2.2-0.2 3.7 0.7 15.9-0.2 1.2-2.4 6.1-0.8 3.4-0.1 1.8 0.2 1.4 6 9.8 2.1 4.6 0.6 0.7 0.8 0.4 1.1 0.2 1.2-0.1 2.1-0.5 3.9-1.3 2.2-0.3 1.4 0.2 1 0.3 2.8 1.2 1.3 6.3 1.1 1.6 0.9 0 1.6 0.1 2 0.5 3.7 1.5 1.3 1.3 0.3 1.1-1 1.7-1.8 2.2-0.5 0.8-0.6 0.7-2.9 5.2-1 2.3-1.2 3.6-17.9-11-25.5-11.3-27.6-6.6-12.8 0-9.8-2.3-53.3 0.5-22.8-5.8-8.1-1.5 1-2.5 4-9.1 2-3.2 9.5-8.4 1.6-1.9 1.1-1.5 0.5-4.7 0.7-3.2 0.8-1.9 0.9-1.2 0.8-0.8 0.7-0.5 8.2-8.2 1.9-2.6 1-1.8 0.1-1.3-0.5-8.5 0.6-4.5 1.1-4.6 0.8-2.1 0.8-1.4 0.8-0.7 1.5-0.9 0.9-0.2 2.6-0.3 1.5-0.6 1.5-1.2 4.4-5.5 2.1-1.9 5.5-3.2 2.4-1.7 2.7-2.4 1-1.5 0.5-1.5-0.2-1-0.3-1-0.5-0.8-0.7-0.6-0.8-0.6-0.9-0.4-4.3-1.4z" id="SVLI" name="La Libertad"></path>
    <path class="dept cursor-pointer" data-name="La Paz" data-unis="2" data-id="la-paz" d="M413 423.6l1.2-3.6 1-2.3 2.9-5.2 0.6-0.7 0.5-0.8 1.8-2.2 1-1.7-0.3-1.1-1.3-1.3-3.7-1.5-2-0.5-1.6-0.1-0.9 0-1.1-1.6-1.3-6.3 0.8-3.2 1.1-0.7 2.7-3 1.9-4.1 3.2-9.6 0.8-4.2 0.2-2.7-0.7-1.9-2.9-5.1-1.1-3.1-0.7-3.3 0.2-1.8 0.5-1.2 0.9-0.5 1-0.2 1.2 0 1.1 0.2 6 1.9 1.2 0.2 1.1 0.1 1.9-1.5 11.3-14.4 13.6-12.8 16.5 6.1 6.5 3.5 7.1 2.2 1.3 0 1.7-0.3 0.7-0.2 0.8-0.4 0.6-0.7 0.8-1.8 0.7-0.8 0.8-0.4 1-0.3 4.7-0.6 2.1-0.7 2.6-1.6 2.8 7.6 0.1 6.3-0.4 6.6 0.4 2.6 0.7 1.6 1.2 0.1 1.2 0 1.2-0.2 8.9-2.8 1.6-0.1 1.7 0.3 2.9 1.1 1 1.3 9.3 19.8 0.3 1.1 0.1 1.1-0.1 2.5-0.4 2.2-2.9 7.9-0.2 0.9-0.3 0.9-0.3 1.3 0 0.6 0 0.1-1.6 8.4 0 1.8 0.5 6.7 0.5 2.2 0.6 1.6 2.4 2.7 2.3 1.8 3.3 2 2.6 2.5 2.4 3.2 0.9 2 0.4 1.6-0.2 1.2-0.5 2.2-0.3 0.9-0.5 0.9-2.3 1.7-0.6 0.7-0.5 0.8-0.3 1-0.1 1.2-0.1 1.2 1.7 15.8-0.1 2.2-0.5 0.8-0.6 0.7-0.7 0.8-0.8 0.5-3.5 1.7-2.7 1.1-1.7 1-1.5 1.2-0.6 0.7-0.5 0.8-0.5 1-0.3 0.9-0.3 1.1-0.9 11.6 0 0.1-87.6-40.9-26.6-16.4z" id="SVPA" name="La Paz"></path>
    <path class="dept cursor-pointer" data-name="San Vicente" data-unis="2" data-id="san-vicente" d="M520.2 293.8l0.5-11.1 0.7-1.2 1-1.4 2.4 0.1 2.2 0.5 3.9 1.3 2.6-0.4 3.6-1.2 11.7-5.2 2.5-0.6 2.7 0.6 2.2 0.2 1.3-0.8 1.2-1.3 1.9-2.5 1.4-0.7 1.2-0.1 5.4 3.9 1.8 0.5 2.7 0.2 12-0.1 2.4 0.4 5.2 0.3 4.1 0.8 0.9 0.4 6.9 5.1 0.9 0.5 3.9 1.4 2.6 1.5 0.9 0.4 2.6 0.7 1 0.5 0.6 0.9 0.4 2.1 0.3 1 0.5 0.8 0.7 0.6 1.4 0.5 9.5 1.2 3.5 0.9 6.1-4.1 1 9.1 11.6 11.6 4.3 6.8-23.6 17.2-2.1 6.8-5.6 7.7-7.5 4.8-8.3-1.9-10.8 13.4-0.5 2.4-6.2 1.1-2.1 3-0.9 11.7-3.2 11.5-17.1 32.6-1.6 4.1-0.9 4.9-0.5 12-0.9 2.6-6.6 6.4-3.5 5.2-2.2 4.2-3.6 2.9-7.3 1.1-4.5 1.7-2.8 3.8-1.5 5.5 0 0.8 0.2 0.1-5.6-2.6 0-0.1 0.9-11.6 0.3-1.1 0.3-0.9 0.5-1 0.5-0.8 0.6-0.7 1.5-1.2 1.7-1 2.7-1.1 3.5-1.7 0.8-0.5 0.7-0.8 0.6-0.7 0.5-0.8 0.1-2.2-1.7-15.8 0.1-1.2 0.1-1.2 0.3-1 0.5-0.8 0.6-0.7 2.3-1.7 0.5-0.9 0.3-0.9 0.5-2.2 0.2-1.2-0.4-1.6-0.9-2-2.4-3.2-2.6-2.5-3.3-2-2.3-1.8-2.4-2.7-0.6-1.6-0.5-2.2-0.5-6.7 0-1.8 1.6-8.4 0-0.1 0-0.6 0.3-1.3 0.3-0.9 0.2-0.9 2.9-7.9 0.4-2.2 0.1-2.5-0.1-1.1-0.3-1.1-9.3-19.8-1-1.3-2.9-1.1-1.7-0.3-1.6 0.1-8.9 2.8-1.2 0.2-1.2 0-1.2-0.1-0.7-1.6-0.4-2.6 0.4-6.6-0.1-6.3-2.8-7.6 7.3-7.2 0.8-1.3 0.9-1.8 1.3-5.4 6.9-18.3z" id="SVSV" name="San Vicente"></path>
    <path class="dept cursor-pointer" data-name="Usulután" data-unis="3" data-id="usulutan" d="M656.4 317.9l15.4-1.8 4.4 0.3 2.3 2.9 3.2 5 2.5 2.4 3.3 2.3 2.9 1 9.2 1.2 2.3 0.7 1.2 1 0 1.1-1.8 5-0.2 1.1 0 1.2 0.7 3.2 1.1 2.7 0.5 1.6 0.2 1.4-0.2 1-0.3 1-0.6 0.8-0.6 0.7-0.8 0.4-2.1 0.7-0.8 0.5-0.6 0.7-0.5 0.8-0.3 1-0.1 1.2 0 2.3 0.2 1.1 0.3 1 4.5 9.6 0.5 1.5 0 0.9-0.8 3 0 1.6 0.3 2 1.5 5.1 0.2 1.5-0.1 0.8-0.5 1-0.1 0.1-2 1.9-0.6 0.7-0.5 0.9-0.3 1-0.1 1.2 0.1 2.3-0.2 2.4-0.4 1-0.5 0.6-0.7 0-0.6-0.5-0.5-0.6-0.4-0.4-0.6-0.2-0.7 0.2-0.6 0.7-0.3 1-0.1 1.2 0.1 1.1 0.6 5.2 0.1 2.5 1.2 7.1 0.4 1.2 0.6 0.4 0.6 0.1 1.8-0.7 1.5 0.5 0.6 1 0.2 1.2-0.1 2.5 0.2 2.3 0.6 3 2 6.9 2 4.1 3.8 6.1 2.6 3.4 0.7 0.5 0.8 0.5 2.8 1.3 2 0.5 7.4 0.8 1.7 1 1.9 1.6 3.3 4 1.6 1.4 1.1 0.8 0.9-0.1 1.1 0.1 0.9 0.4 0.8 0.5 1.5 1.2 0.8 0.5 0.9 0.4 1.1 0.1 1 0 1.1-0.3 1.9-0.8 3.5-1.9 1.2-0.1 1.6 0.2 1.2 1.4 0.7 1.1 0.3 1.2 0.2 1.1 0 5.7 0.9 1.4 1.4 1.2 2.5 1.3 0.9 1.2 0.2 1.1-2.8 5.1-0.4 1.3-0.2 1.2 0.1 1.9 0.7 0.8 0.9 0.2 10.2-1.6 1.2 0.1 1 0.2 1 0.4 23 26.8-3.5 1.4-32.4 2.7-34.8-5.3-9 2.9-4.8-1.6-5.5-1.2 0-2.4 8.5-0.6 0.6-5.9-4.5-7.4-6.9-4.8 1.5 3 4.5 6.3 1.7 3.9-13.6-0.5-6.6 0.7-5.7 2.4 3.7 1.6 2.9-0.5 3.5-1 5.3-0.1 0 2.9-8.7 3.6-9.7-0.3-7.5-4.6-2.3-9.5 3.7 1.7 1.4 0.9 5.2-5.2 0-2.4-6.5-2.9-7-10-4.7-2.9 1.2 5.5 2.2 4.6 1.1 4.1-1.7 4-5.5-3.2-15.9-4-6.8-3.6-6.2-11.1-2.9-1.8-4.4-0.4-12.4-2.5-5.5 0-15.2 2.9-21.2 0.5-4.7 2.1 2.6 2 2.7 1.5 2.8 0.5 2.4-1.1 10.6 2.4 30.2-3.2 3.3 0.5 4.9 2.7 2.6 2.4 4.7 5.7 3.1 2.4 0 2.9-7-1.2-5.1-1-11.2-3.3 0 2.6 30.8 6.3 16.1 5.3 7.2 7.1-2.7 4.6-6.7-1.1-7.5-4-4.9-3.7-5.5-2.8-97-18.1-11.6-5.4-0.2-0.1 0-0.8 1.5-5.5 2.8-3.8 4.5-1.7 7.3-1.1 3.6-2.9 2.2-4.2 3.5-5.2 6.6-6.4 0.9-2.6 0.5-12 0.9-4.9 1.6-4.1 17.1-32.6 3.2-11.5 0.9-11.7 2.1-3 6.2-1.1 0.5-2.4 10.8-13.4 8.3 1.9 7.5-4.8 5.6-7.7 2.1-6.8 23.6-17.2z" id="SVUS" name="Usulután"></path>
    <path class="dept cursor-pointer" data-name="Cuscatlán" data-unis="2" data-id="cuscatlan" d="M410.9 171.4l6.6-3.4 1.2 0 1.3 0.1 4.7 2.4 9.7 3.5 1.9 1.1 2.7 2.4 6.3 4.5 0.6 0.8 1.7 3 8.2 9.8 0.7 1 0.2 0.7 0 0.1-0.3 1.6 0 1.1 0.1 0.9 0.3 1 0.6 1.8 1 2.1 2 2.9 1.7 1.6 1.4 0.9 8 3.8 6.8-0.5 5.8 2.2 6.2 0.7-5.5 8.8-0.4 0.9-1.1 1.6-1.3 1.4-1.4 1.2-5 2.9-0.8 0.6-0.6 0.6-0.6 0.9-0.3 0.9-0.3 1-0.2 1.2 0.9 2.5 1.8 3.3 7.3 10.4 2.3 2.4 3.1 2.1 1.8 2.1 1.4 3 0.3 1.7 0.4 1.6 1.9 3.4 0.5 1.5 0.5 2.5 0.9 1.7 1.5 2 3.6 3.9 2.4 3.7 0.4 1 3.4 1.9 13 3.6-6.9 18.3-1.3 5.4-0.9 1.8-0.8 1.3-7.3 7.2-2.6 1.6-2.1 0.7-4.7 0.6-1 0.3-0.8 0.4-0.7 0.8-0.8 1.8-0.6 0.7-0.8 0.4-0.7 0.2-1.7 0.3-1.3 0-7.1-2.2-6.5-3.5-16.5-6.1-0.3-11.6-0.8-2-0.7-2-1.8-2.3-6.6-12.5-0.5-1.8 0.6-8.3-0.3-1.7-0.7-1.1-1-0.3-1.4-1-1.6-1.6-4.7-7-0.7-0.6-1-0.4-1.1-0.2-2.4-0.1-2.6-2.1-3.4-4-10-15.7-1.1-3 0.7-0.6 2.7-1.2 1-0.7 0.7-0.8 0.7-1.5-0.4-0.8-2.3-3-7.8-12.8-3.5-7.4-0.8-2.9-0.4-3.3 0.2-0.6 0.6-0.5 0.7-0.6 0.7-0.6 1-1.6 0.3-0.9 0.3-1.1 0.3-2.3 0.5-2.1 0.2-1.6 0-0.4 0.1-0.7-0.3-3.7-1.2-6.4-0.1-1.7 0.3-1.2 1.8-3.7 1.7-4.6 0.2-3.8z" id="SVCU" name="Cuscatlán"></path>
    <path class="dept cursor-pointer" data-name="San Salvador" data-unis="14" data-id="san-salvador" d="M363.6 177.2l2.7 0.4 1.5-0.8 4.9-3.5 2.9-1 7.8 3.4 5.2 0.1 2.4-4.8 5.3 4.2 4.2 1 10.4-4.8-0.2 3.8-1.7 4.6-1.8 3.7-0.3 1.2 0.1 1.7 1.2 6.4 0.3 3.7-0.1 0.7 0 0.4-0.2 1.6-0.5 2.1-0.3 2.3-0.3 1.1-0.3 0.9-1 1.6-0.7 0.6-0.7 0.6-0.6 0.5-0.2 0.6 0.4 3.3 0.8 2.9 3.5 7.4 7.8 12.8 2.3 3 0.4 0.8-0.7 1.5-0.7 0.8-1 0.7-2.7 1.2-0.7 0.6 1.1 3 10 15.7 3.4 4 2.6 2.1 2.4 0.1 1.1 0.2 1 0.4 0.7 0.6 4.7 7 1.6 1.6 1.4 1 1 0.3 0.7 1.1 0.3 1.7-0.6 8.3 0.5 1.8 6.6 12.5 1.8 2.3 0.7 2 0.8 2 0.3 11.6-13.6 12.8-11.3 14.4-1.9 1.5-1.1-0.1-1.2-0.2-6-1.9-1.1-0.2-1.2 0-1 0.2-0.9 0.5-0.5 1.2-0.2 1.8 0.7 3.3 1.1 3.1 2.9 5.1 0.7 1.9-0.2 2.7-0.8 4.2-3.2 9.6-1.9 4.1-2.7 3-1.1 0.7-0.8 3.2-2.8-1.2-1-0.3-1.4-0.2-2.2 0.3-3.9 1.3-2.1 0.5-1.2 0.1-1.1-0.2-0.8-0.4-0.6-0.7-2.1-4.6-6-9.8-0.2-1.4 0.1-1.8 0.8-3.4 2.4-6.1 0.2-1.2-0.7-15.9 0.2-3.7 0.6-2.2 0.7-0.6 0.6-0.7 0.2-0.9-0.4-0.9-2.9-1.4-0.8-0.8-0.1-1.2 0.9-2.2 1.2-2.3 0.2-1.2-0.4-1.4-2.5-3.4-1.6-1.4-0.7-0.5-5.4-2.9-0.8-0.6-0.7-1.1-0.6-1.7-0.7-3-0.4-1.3-0.7-0.9-1.5-1.1-0.6-0.7-0.5-0.8-0.4-1-0.3-3.7 0.4-3.8-1.9-2.5-1.2-0.1-1-0.3-0.6-0.8-0.2-1.5 1.7-4.8 2.6-4.9 0.3-1 0.3-1.6 0.4-6.2 0.7-3.6 1.2-2.8 0.8-3 0.7-6.8 0.5-1.8 0.7-1.9 1.2-1.4 0.7-0.6 0.9-0.5 1.9-0.7 0.8-0.5 0.4-0.9 0.5-2.2 1.9-21.6 0.4-1.2 0.7-0.7 1-0.3 2.8 0.1 0.3-0.3 0.1-0.4 0.5-2.2 1-2.6-0.1-0.6-0.6-0.8-2-1.1-1.4-1.1-1.2-0.7-1-0.4-1.1-0.2-1.1-0.2-1.2 0-3.5 0.3-1.1-0.1-3-0.9-1.8-0.9-1.6-1-1-1.4-1.1-2.1-1.9-4.4-1.1-2-0.9-1.3-2.3-1.8-0.2-1.4 0.1-2.1 2-8.3 0.4-13z" id="SVSS" name="San Salvador"></path>
 </g>
</svg>
        </div>

        <!-- Legend -->
        <div class="map-legend">
          <div class="legend-item">
            <span class="legend-dot" style="background:rgba(45,212,191,0.5); border:1px solid var(--teal);"></span>
            <span>Hover — Vista previa</span>
          </div>
          <div class="legend-item">
            <span class="legend-dot" style="background:rgba(232,200,71,0.4); border:1px solid var(--gold);"></span>
            <span>Seleccionado</span>
          </div>
          <div class="legend-item">
            <span class="legend-dot" style="background:rgba(14,60,100,0.65); border:1px solid rgba(100,180,255,0.5);"></span>
            <span>Sin seleccionar</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== MODAL PREMIUM DE UNIVERSIDADES POR DEPARTAMENTO ====== -->
  <div class="map-modal-overlay" id="mapModalOverlay">
    <div class="map-modal" id="mapModal">
      <!-- Header del departamento -->
      <div class="modal-dept-header">
        <div>
          <h2 class="modal-dept-name" id="modalDeptName">Departamento</h2>
          <div class="modal-dept-meta">
            <span class="modal-dept-tag" id="modalDeptUniCount">0 universidades</span>
            <span class="modal-dept-tag" style="background:rgba(232,200,71,0.12); border-color:rgba(232,200,71,0.3); color:var(--gold);" id="modalDeptRegion">El Salvador</span>
          </div>
        </div>
        <button class="modal-close-btn" id="mapModalClose" aria-label="Cerrar">✕</button>
      </div>
      <!-- Cards de universidades (generadas por JS) -->
      <div class="modal-body" id="mapModalBody">
        <!-- se llena dinámicamente -->
      </div>
    </div>
  </div>

       
 <g id="features">
    <path class="dept cursor-pointer" data-name="Ahuachapán" data-unis="2" data-desc="Universidad Gerardo Barrios, UNIVO" d="M183.1 180l3.2 1.3 6.8 4.3 1.3 1.2 0.7 0.8 0.4 0.8 0.8 3.2 0.4 0.9 0.6 0.9 1.3 1.3 1.6 1.2 1.3 1.4 0.1 0.1 0.8 1.5 0.3 0.9-1.1 6.9-7.5 25.9-6.7 2.8-8.2 2.3-4.6 0.7-1 0.7-0.8 1.1-0.4 2.5-0.1 1.6 0.9 7.2 0.6 2.3 0.7 1.7 0.9 1.8 0.3 0.9-0.2 1.3-7.6 11.7-0.7 2.1-0.2 1.6 3.1 10 0.4 2.2-0.5 1.5-1.1 1.8-2.8 3.4-1.1 2.1-0.6 1.6-0.3 3.6-0.3 1-4.8 8.6-0.7 1.9-2.1 8.6-1.9 0.8-3.1 0.4-11.3-0.7-2.3-0.5-0.8-0.5-2.7-2.6-4.9-5.7-0.8-0.6-1.5-1.1-0.9-0.4-1-0.4-1.4-0.1-1.5 0-2.1 0.4-1.3 0.4-1.1 0.5-0.9 0.6-1.1 0.9-2.8 3.4-5.9 9.4-0.9 1.4-25.4-11.6-29-14-6-25.1-0.1-6 0.9-6.3 2-6.1 3-5.6 4.5-4.6 10.3-5.5 4.6-3.8 3.4-5.4 2.5-5.6 3.2-5.5 5.6-4.9 36.1-25.3 7.9-8 4-2.7 16.7-6.3 5.5-0.4 6.8 1.8 9.8 7.6 5.5 2.3 5.5-3 0.1-2.2-0.3-0.6z" id="SVAH" name="Ahuachapán"></path>
    <path class="dept cursor-pointer" data-name="Santa Ana" data-unis="4" data-desc="UNASA, UNICO, Galileo, UCO" data-universities='[{"name":"UNASA","image":"https://campussostenible.unasa.edu.sv/images/UNASA2024/UNASA_SUR_2024.jpg","description":"Universidad Autónoma de Santa Ana.","careers":"Medicina, Enfermería, Laboratorio Clínico.","website":"https://www.unasa.edu.sv"},{"name":"Universidad Católica de Occidente","image":"https://i.ytimg.com/vi/28XYWdg6QJw/hq720.jpg?sqp=-oaymwE7CK4FEIIDSFryq4qpAy0IARUAAAAAGAElAADIQj0AgKJD8AEB-AH-CYAC0AWKAgwIABABGFcgYShlMA8=&rs=AOn4CLBxZoHvR9NMNLy6U48wmWikOfqrCQ","description":"Universidad privada.","careers":"Derecho, Ingeniería, Arquitectura.","website":"https://www.uco.edu.sv"}]' d="M194.1 232.6l7.5-25.9 1.1-6.9-0.3-0.9-0.8-1.5-0.1-0.1-1.3-1.4-1.6-1.2-1.3-1.3-0.6-0.9-0.4-0.9-0.8-3.2-0.4-0.8-0.7-0.8-1.3-1.2-6.8-4.3-3.2-1.3-2.4-5.6-0.3-3 1-3.2 16.1-28.4 7.5-8.2 9.4-7.3 9.9-4.6 40.3-9.7 2.5-2.4-0.1-6-1.2-1.6-4.8-2.4-1.6-1.6-0.7-2.5-0.7-5.9-0.5-1.8-2.2-4.2-1-1.5-2.2-2-2.6-1.2-7.9-1-2.3-5.5 2.2-3.3 3.8-2.7 2.4-3.8-1.1-4.5-2.7-3.8-1.7-4.4 2.5-6.1 5.3-4 5.5 0.5 4.1 4.1 1.1 7.2 5.1-3.7 9.3-11.2 2.6-1.1 4.1-1.8 5.9 0.8 4.3 2.3 4.2 1.5 5.9-1.2 8.6-8.8 3.8-1.5 0.5 7.5 1.9-3 0.4-0.9 10.8 7.9 0.1 0.1 5.8 0.9 14.3 13.4 2.5 2.8 0.5 0.7 1.2 2.9 1.7 2.9 0.8 0.8 1.3 1.1 5.6 2.9 0.8 0.7 1.8 2.4 6.9 4.4-4.8 14-1.5 7.8-3.3 3.7-14 5.7 0 7.7 0.3 4.3-0.1 1.5-0.7 1.8-7.3 10.1-1.3 1.3-0.8 0.6-0.8 0.5-1 0.3-1 0.3-1.2 0.2-3.8 0.2-1 0.2-1 0.3-0.9 0.4-0.8 0.5-0.7 0.6-4.4 8.1-0.9 1.2-0.7 0.7-0.9 0.5-0.9 0.4-7.1-0.5-7.2 24.8 1.1 8.4 4.2 3.8 5.6 1.1 5.6 0 4.4-1.2-4 21.4-0.7 5.5 0.1 1.2-0.6 7.5-0.4 1.2-0.8 1-1.9 0.9-1.4 0-1.3 0-1 0.1-0.8 0.4-3 4.9-1.2 0.8-1.1 0.1-0.9-0.3-1-0.1-0.9 0.3-0.8 0.5-0.6 0.8-0.5 2.1-0.5 10.4 0.4 6.9-0.7 8.3-6.6 18-1.8 10.7-17.9-3.4-4.7-1.6-0.6-0.8-0.6-0.8-2.2-5.6-5-4.7-8-5.9-3.6-2.2-2.5-1.1-1 0.2-0.8 0.6-0.6 0.7-1 1.6-1.2 2.8-0.5 2.1 0 3.5-0.3 0.9-0.8 0.9-1.4 0.7-2.7 0.6-1.7-0.4-1.2-0.4-0.9-0.7-0.6-0.7-0.5-0.9-0.4-1.2-0.9-1.3-1.7-1.8-2.4-0.4-1.2-0.8-0.5-0.8 0.2-1 0.4-0.8 0.7-0.8 1.4-1.2 0.6-0.6 0.5-0.9 0.3-1.1 0.1-1-0.4-2-1.7-2.8-2.3-2.1-1.2-1.3-0.8-1.1-1.2-2.8-1.1-1.5-6.1-6-2.2-1.6-1.7-0.8-1.2 0-1.1 0.1-2.1 0.6-3.4 0.1-8.7-2.2z" id="SVSA" name="Santa Ana"></path>
    <path class="dept cursor-pointer" data-name="Chalatenango" data-unis="1" data-desc="Universidad Gerardo Barrios" d="M324.5 181.7l-4.4 1.2-5.6 0-5.6-1.1-4.2-3.8-1.1-8.4 7.2-24.8 7.1 0.5 0.9-0.4 0.9-0.5 0.7-0.7 0.9-1.2 4.4-8.1 0.7-0.6 0.8-0.5 0.9-0.4 1-0.3 1-0.2 3.8-0.2 1.2-0.2 1-0.3 1-0.3 0.8-0.5 0.8-0.6 1.3-1.3 7.3-10.1 0.7-1.8 0.1-1.5-0.3-4.3 0-7.7 14-5.7 3.3-3.7 1.5-7.8 4.8-14-6.9-4.4-1.8-2.4-0.8-0.7-5.6-2.9-1.3-1.1-0.8-0.8-1.7-2.9-1.2-2.9-0.5-0.7-2.5-2.8-14.3-13.4 14.5 2.3 11.6 5.6 19.2 4.1 9.5 6.7 17.3 5.2 3.6-3.2 5.4-10.5 3-3.1 3.3-0.9 2.2 0.4 1.9 1.7 2.1 3 1.9 3.5 0.8 3.9-0.3 3.9-1.5 3.8 4.3 3.7 12.8 1.5 6 3 2.4 4.8 1.5 11.9 1.7 3.5 9.5 7.2 3.4 4.2 3.4 5.7 1.4 4.9 0.2 1.8 0.4 3.2 1.2 4.1 3.3 2.4 3.7-0.8 4.4-2.6 5.3-2.3 5.8 0.1 9.3 6 7.9 9.9 5.6 11.6 2.5 10.9 6.6-1 16.4 1.3 4.5-1.8 3.9-2.4 3.3-0.3 2.7 4.4-0.4 6.5-2.2 6.4-0.2 5.5 5.9 3.8 8 1.1 1.8 0.6 2.7 2.4 1.8 2.6 0.9 3.2-0.2 2-17.3 2.6-7.7 3.8-2.9 3.8-2.5 2-4.5 2.1-23.1 7.6-5.6 2.1-5.2 1.3-9.8-0.9-11.1 0.3-6.2-0.7-5.8-2.2-6.8 0.5-8-3.8-1.4-0.9-1.7-1.6-2-2.9-1-2.1-0.6-1.8-0.3-1-0.1-0.9 0-1.1 0.3-1.6 0-0.1-0.2-0.7-0.7-1-8.2-9.8-1.7-3-0.6-0.8-6.3-4.5-2.7-2.4-1.9-1.1-9.7-3.5-4.7-2.4-1.3-0.1-1.2 0-6.6 3.4-10.4 4.8-4.2-1-5.3-4.2-2.4 4.8-5.2-0.1-7.8-3.4-2.9 1-4.9 3.5-1.5 0.8-2.7-0.4-3.6-1.7-1.2-0.3-6.1-0.9-6.8-3.9-3.9-0.8-3.6 2.8-3.5 1.9-4.4 0.9-3.1 1.2-1.2 2.6-1.7 2.7z" id="SVCH" name="Chalatenango"></path>
    <path class="dept cursor-pointer" data-name="Cabañas" data-unis="1" data-desc="UNIVO sede Cabañas" d="M490.3 217.5l11.1-0.3 9.8 0.9 5.2-1.3 5.6-2.1 23.1-7.6 4.5-2.1 2.5-2 2.9-3.8 7.7-3.8 17.3-2.6 0 0.7 2.3-0.9 9.5-1.5 9.6 0.7 28.4 9.1 16.6 1.2 3.6 1.6 3.4 3.3-0.3 1.2-0.1 0.9-0.2 0.2-0.4 0.3-0.1 0-1.5-0.2 0.4 0.9 1.9 4.2-0.2 4.5-1.3 4.7-0.8 5.7 1 3.7 4.1 10.2 0.3 6.1-3.3 5.5-0.7 0.6-1.7 1.5 2.4 4.8-0.6 4.6-2.6 3.9-4.1 2.6 0.8 5.3-3.5 5.4-3.4 6.7 0 0.1-6.1 4.1-3.5-0.9-9.5-1.2-1.4-0.5-0.7-0.6-0.5-0.8-0.3-1-0.4-2.1-0.6-0.9-1-0.5-2.6-0.7-0.9-0.4-2.6-1.5-3.9-1.4-0.9-0.5-6.9-5.1-0.9-0.4-4.1-0.8-5.2-0.3-2.4-0.4-12 0.1-2.7-0.2-1.8-0.5-5.4-3.9-1.2 0.1-1.4 0.7-1.9 2.5-1.2 1.3-1.3 0.8-2.2-0.2-2.7-0.6-2.5 0.6-11.7 5.2-3.6 1.2-2.6 0.4-3.9-1.3-2.2-0.5-2.4-0.1-1 1.4-0.7 1.2-0.5 11.1-13-3.6-3.4-1.9-0.4-1-2.4-3.7-3.6-3.9-1.5-2-0.9-1.7-0.5-2.5-0.5-1.5-1.9-3.4-0.4-1.6-0.3-1.7-1.4-3-1.8-2.1-3.1-2.1-2.3-2.4-7.3-10.4-1.8-3.3-0.9-2.5 0.2-1.2 0.3-1 0.3-0.9 0.6-0.9 0.6-0.6 0.8-0.6 5-2.9 1.4-1.2 1.3-1.4 1.1-1.6 0.4-0.9 5.5-8.8z" id="SVCA" name="Cabañas"></path>
    <path class="dept cursor-pointer" data-name="Morazán" data-unis="2" data-desc="UGB, Gerardo Barrios" d="M736.4 228.6l1.5-2.6 2.5-2.8 4.5-1.9 3.3 0.5 2.4-0.5 1.8-5.3-0.6-1-3.4-3.1-0.8-1.4-0.3-7.7 0.1-1.3 3.7-1.4 5.7 0.6 0.9 0.2 9 2 6 0.2 19.6-2.4 9.9 0.6 4.3 3.6 5.5 14.3 5.4 6.6 7 6.3 6.1 7.4 3.1 9.5 17.1-8.5 5.6-1.5 0.9 0.1 1.2 7.6 1.6 6.3 0.7 10.9-1.5 23.5 0.1 1.3 0.2 1 0.5 0.8 1.7 2.2 0.5 0.9 0.4 0.9 0.3 0.9 0.4 2.4-0.2 7.4-1.1 8.9-2.2 10.4-8.6 22.3-0.7 7.3-2.7 4.2-11.6 12.4-8.1-1.3-3.9 0.2-14.7 4.6-2.8 0.3-1.8-0.3-0.6-0.6-0.6-1.1-0.3-0.8-0.9-1-1.3-1.2-5.5-3.3-0.8-0.6-0.6-0.8-0.4-0.8-1.1-6.9-0.6-2.1-1.4-2.6-1.6-2.1-1.3-0.8-1.4-0.5-14.6 1.2-2.1 0.4-1.9 0.8-3 1.8-2.1 0.6-0.6-0.5-0.1-0.6 0.5-0.9 2.2-3 0.4-0.9 0.1-1-0.6-1-0.9-0.9-2.7-1.7-0.8-0.7-0.6-0.7-0.3-0.9-0.3-1.1-0.2-1.1-0.8-1.5-1.4-1.9-3.5-2.6-1.4-1.4-0.7-2.3-1.2-1.9-4.8-4.3-1.9-2.3-1.2-1.8-0.6-9.7 0-1 0.4-0.8 0.7-0.7 12.3-8.9 1.7-1 2.9-1 0.9-0.5 0.7-0.7 0.6-0.7 0.3-1.1 0-1.4-0.7-2.3-1-1.5-0.8-1-14.2-9.7-2.1-1.9-1.4-1.5-0.3-1-0.3-1-0.1-3.8 0.3-2.1 0.2-0.7 0.2-0.7 0.5-0.8 1.1-1.4 0.7-0.6 0.8-0.5 0.6-0.7 0.6-0.8 0.4-0.8 0.4-1 0.2-1.1 0-2.3-1.2-3.2-1.5-2.9-0.6-1.7-0.1-1.3-0.7-3.1-4.4-10.4-2.1-2.9z" id="SVMO" name="Morazán"></path>
    <path class="dept cursor-pointer" data-name="San Miguel" data-unis="5" data-desc="UES Oriente, UGB, UNICO, UNIVO, Modular" d="M639.5 290.4l0-0.1 3.4-6.7 3.5-5.4-0.8-5.3 4.1-2.6 2.6-3.9 0.6-4.6-2.4-4.8 1.7-1.5 0.7-0.6 10-0.5 7-4.3 6.8-2.9 15.2-3.3 3.5 0.2 6 2.3 2.8 0.3 0.4-1.1 0-2.3 0.3-2.4 1.2-1.4 2.4-0.1 3 0.5 3 0.9 2.4 1 2.6-4.8 4.1-1.5 4.5-0.4 4.1-1.4 3-2.8 1.2-2.3 2.1 2.9 4.4 10.4 0.7 3.1 0.1 1.3 0.6 1.7 1.5 2.9 1.2 3.2 0 2.3-0.2 1.1-0.4 1-0.4 0.8-0.6 0.8-0.6 0.7-0.8 0.5-0.7 0.6-1.1 1.4-0.5 0.8-0.2 0.7-0.2 0.7-0.3 2.1 0.1 3.8 0.3 1 0.3 1 1.4 1.5 2.1 1.9 14.2 9.7 0.8 1 1 1.5 0.7 2.3 0 1.4-0.3 1.1-0.6 0.7-0.7 0.7-0.9 0.5-2.9 1-1.7 1-12.3 8.9-0.7 0.7-0.4 0.8 0 1 0.6 9.7 1.2 1.8 1.9 2.3 4.8 4.3 1.2 1.9 0.7 2.3 1.4 1.4 3.5 2.6 1.4 1.9 0.8 1.5 0.2 1.1 0.3 1.1 0.3 0.9 0.6 0.7 0.8 0.7 2.7 1.7 0.9 0.9 0.6 1-0.1 1-0.4 0.9-2.2 3-0.5 0.9 0.1 0.6 0.6 0.5 2.1-0.6 3-1.8 1.9-0.8 2.1-0.4 14.6-1.2 1.4 0.5 1.3 0.8 1.6 2.1 1.4 2.6 0.6 2.1 1.1 6.9 0.4 0.8 0.6 0.8 0.8 0.6 5.5 3.3 1.3 1.2 0.9 1 0.3 0.8 0.6 1.1 0.6 0.6 1.8 0.3 2.8-0.3 14.7-4.6 3.9-0.2 8.1 1.3-2.5 3.3-0.8 4.9 0.2 11.7 1.5 5.6 0 1.2-0.4 1.3-1.4 1.7-1.1 0.9-1 0.8-1.6 0.9-0.7 0.6-0.6 1.5-0.3 2.5 0.2 9.9 1.1 6.8 0.3 1 0.5 0.9 3.5 5.6 0.4 0.9 0.4 1 0.1 0.9-4.8 27.8 0.1 1.6 0.6 2.2-0.3 2.4-4.9 16.1-0.8 0.9-1.8 0.5-1.2 0-1.3-0.3-3.9-1.4-3.2-1.9-3.1-2.3-0.8-0.3-1 3.8-0.5 2.9-2.1 30.2 0 0.1-2.6-0.5-2.5 0-2.8 1.1-23-26.8-1-0.4-1-0.2-1.2-0.1-10.2 1.6-0.9-0.2-0.7-0.8-0.1-1.9 0.2-1.2 0.4-1.3 2.8-5.1-0.2-1.1-0.9-1.2-2.5-1.3-1.4-1.2-0.9-1.4 0-5.7-0.2-1.1-0.3-1.2-0.7-1.1-1.2-1.4-1.6-0.2-1.2 0.1-3.5 1.9-1.9 0.8-1.1 0.3-1 0-1.1-0.1-0.9-0.4-0.8-0.5-1.5-1.2-0.8-0.5-0.9-0.4-1.1-0.1-0.9 0.1-1.1-0.8-1.6-1.4-3.3-4-1.9-1.6-1.7-1-7.4-0.8-2-0.5-2.8-1.3-0.8-0.5-0.7-0.5-2.6-3.4-3.8-6.1-2-4.1-2-6.9-0.6-3-0.2-2.3 0.1-2.5-0.2-1.2-0.6-1-1.5-0.5-1.8 0.7-0.6-0.1-0.6-0.4-0.4-1.2-1.2-7.1-0.1-2.5-0.6-5.2-0.1-1.1 0.1-1.2 0.3-1 0.6-0.7 0.7-0.2 0.6 0.2 0.4 0.4 0.5 0.6 0.6 0.5 0.7 0 0.5-0.6 0.4-1 0.2-2.4-0.1-2.3 0.1-1.2 0.3-1 0.5-0.9 0.6-0.7 2-1.9 0.1-0.1 0.5-1 0.1-0.8-0.2-1.5-1.5-5.1-0.3-2 0-1.6 0.8-3 0-0.9-0.5-1.5-4.5-9.6-0.3-1-0.2-1.1 0-2.3 0.1-1.2 0.3-1 0.5-0.8 0.6-0.7 0.8-0.5 2.1-0.7 0.8-0.4 0.6-0.7 0.6-0.8 0.3-1 0.2-1-0.2-1.4-0.5-1.6-1.1-2.7-0.7-3.2 0-1.2 0.2-1.1 1.8-5 0-1.1-1.2-1-2.3-0.7-9.2-1.2-2.9-1-3.3-2.3-2.5-2.4-3.2-5-2.3-2.9-4.4-0.3-15.4 1.8-4.3-6.8-11.6-11.6-1-9.1z" id="SVSM" name="San Miguel"></path>
    <path class="dept cursor-pointer" data-name="La Unión" data-unis="1" data-desc="UGB sede La Unión" d="M836.2 370.7l11.6-12.4 2.7-4.2 0.7-7.3 8.6-22.3 2.2-10.4 1.1-8.9 0.2-7.4-0.4-2.4-0.3-0.9-0.4-0.9-0.5-0.9-1.7-2.2-0.5-0.8-0.2-1-0.1-1.3 1.5-23.5-0.7-10.9-1.6-6.3-1.2-7.6 2.1 0.5 5.5 3.6 2.6 0.7 1.7-0.8 4.8-3.8 3-1 3.7 0.2 7.4 1.6 3.6-0.3 3.6-2.6 3.1-3.9 3.8-3.2 5.6-0.2 3.6 2.8 3.6 10.2 3.1 4.6 1.9 1.1 5.3 2.1 2.3 1.2 5.9 5.2 10.6 9.1 6.6 2.7-3.3 5.6-7.6 17.6-1.9 6.3 2.4 6.4-1.8 4.1-3.8 3.6-3.3 4.7-1.3 6.9 0.3 14.3-0.9 7-9 21.9-2.2 10.6 4 8.8 3.8 1.4 8-2.6 5.4 2.1 2.9 3.4 2 4.6 0.6 5.2-0.9 4.9-6.4 7.4-29.6 13.6-0.3-0.1-2.9-1.8-1.9-3.1-2.8-8.3-10.6 18.4-2.7 10.8 6.9 4.8 4.4 1.7 12.4 12 7.1 4.8 0.6 2.2 0 4.7-3 8.2-7.5 5.9-27.9 14.1-7.6 6.1-1.3 6.1 8.7 6-13.3 3.1-51.7-3.6-9.6-1.8 0-0.1 2.1-30.2 0.5-2.9 1-3.8 0.8 0.3 3.1 2.3 3.2 1.9 3.9 1.4 1.3 0.3 1.2 0 1.8-0.5 0.8-0.9 4.9-16.1 0.3-2.4-0.6-2.2-0.1-1.6 4.8-27.8-0.1-0.9-0.4-1-0.4-0.9-3.5-5.6-0.5-0.9-0.3-1-1.1-6.8-0.2-9.9 0.3-2.5 0.6-1.5 0.7-0.6 1.6-0.9 1-0.8 1.1-0.9 1.4-1.7 0.4-1.3 0-1.2-1.5-5.6-0.2-11.7 0.8-4.9 2.5-3.3z m105.9 136.3l-0.3-4.9 2.7-2 5.2 1.7 3.2 7.1 0.2 5.9 1.4 3.9-3.1 1.8-4.9-1.8-3.2 1.5 0-4.2-2-4.1 0.8-4.9z m-14.8-19.2l2.9 1.8 2.5 5.6-2.2 3.2-2.5 2.4-4.9-4.4-1.2-6.1 2.2-2.2 3.2-0.3z" id="SVUN" name="La Unión"></path>
    <path class="dept cursor-pointer" data-name="Sonsonate" data-unis="3" data-desc="UNICO, USONSONATE, UNIVO" d="M106 326.8l0.9-1.4 5.9-9.4 2.8-3.4 1.1-0.9 0.9-0.6 1.1-0.5 1.3-0.4 2.1-0.4 1.5 0 1.4 0.1 1 0.4 0.9 0.4 1.5 1.1 0.8 0.6 4.9 5.7 2.7 2.6 0.8 0.5 2.3 0.5 11.3 0.7 3.1-0.4 1.9-0.8 2.1-8.6 0.7-1.9 4.8-8.6 0.3-1 0.3-3.6 0.6-1.6 1.1-2.1 2.8-3.4 1.1-1.8 0.5-1.5-0.4-2.2-3.1-10 0.2-1.6 0.7-2.1 7.6-11.7 0.2-1.3-0.3-0.9-0.9-1.8-0.7-1.7-0.6-2.3-0.9-7.2 0.1-1.6 0.4-2.5 0.8-1.1 1-0.7 4.6-0.7 8.2-2.3 6.7-2.8 8.7 2.2 3.4-0.1 2.1-0.6 1.1-0.1 1.2 0 1.7 0.8 2.2 1.6 6.1 6 1.1 1.5 1.2 2.8 0.8 1.1 1.2 1.3 2.3 2.1 1.7 2.8 0.4 2-0.1 1-0.3 1.1-0.5 0.9-0.6 0.6-1.4 1.2-0.7 0.8-0.4 0.8-0.2 1 0.5 0.8 1.2 0.8 2.4 0.4 1.7 1.8 0.9 1.3 0.4 1.2 0.5 0.9 0.6 0.7 0.9 0.7 1.2 0.4 1.7 0.4 2.7-0.6 1.4-0.7 0.8-0.9 0.3-0.9 0-3.5 0.5-2.1 1.2-2.8 1-1.6 0.6-0.7 0.8-0.6 1-0.2 2.5 1.1 3.6 2.2 8 5.9 5 4.7 2.2 5.6 0.6 0.8 0.6 0.8 4.7 1.6 17.9 3.4 4.3 1.4 0.9 0.4 0.8 0.6 0.7 0.6 0.5 0.8 0.3 1 0.2 1-0.5 1.5-1 1.5-2.7 2.4-2.4 1.7-5.5 3.2-2.1 1.9-4.4 5.5-1.5 1.2-1.5 0.6-2.6 0.3-0.9 0.2-1.5 0.9-0.8 0.7-0.8 1.4-0.8 2.1-1.1 4.6-0.6 4.5 0.5 8.5-0.1 1.3-1 1.8-1.9 2.6-8.2 8.2-0.7 0.5-0.8 0.8-0.9 1.2-0.8 1.9-0.7 3.2-0.5 4.7-1.1 1.5-1.6 1.9-9.5 8.4-2 3.2-4 9.1-1 2.5-2.6-0.6-23.8-7.8-12.6 2.6-35.6 0.4-5.7-3.9-3.8-14.2-1.4-8.9-4.7-3.9-7.8-4.8-30.4-17.3-0.8-0.4z" id="SVSO" name="Sonsonate"></path>
    <path class="dept cursor-pointer" data-name="La Libertad" data-unis="6" data-desc="UCA, UJMD, UDB, Tecnológica, UTEC, Modular" d="M292.5 284.3l1.8-10.7 6.6-18 0.7-8.3-0.4-6.9 0.5-10.4 0.5-2.1 0.6-0.8 0.8-0.5 0.9-0.3 1 0.1 0.9 0.3 1.1-0.1 1.2-0.8 3-4.9 0.8-0.4 1-0.1 1.3 0 1.4 0 1.9-0.9 0.8-1 0.4-1.2 0.6-7.5-0.1-1.2 0.7-5.5 4-21.4 1.7-2.7 1.2-2.6 3.1-1.2 4.4-0.9 3.5-1.9 3.6-2.8 3.9 0.8 6.8 3.9 6.1 0.9 1.2 0.3 3.6 1.7-0.4 13-2 8.3-0.1 2.1 0.2 1.4 2.3 1.8 0.9 1.3 1.1 2 1.9 4.4 1.1 2.1 1 1.4 1.6 1 1.8 0.9 3 0.9 1.1 0.1 3.5-0.3 1.2 0 1.1 0.2 1.1 0.2 1 0.4 1.2 0.7 1.4 1.1 2 1.1 0.6 0.8 0.1 0.6-1 2.6-0.5 2.2-0.1 0.4-0.3 0.3-2.8-0.1-1 0.3-0.7 0.7-0.4 1.2-1.9 21.6-0.5 2.2-0.4 0.9-0.8 0.5-1.9 0.7-0.9 0.5-0.7 0.6-1.2 1.4-0.7 1.9-0.5 1.8-0.7 6.8-0.8 3-1.2 2.8-0.7 3.6-0.4 6.2-0.3 1.6-0.3 1-2.6 4.9-1.7 4.8 0.2 1.5 0.6 0.8 1 0.3 1.2 0.1 1.9 2.5-0.4 3.8 0.3 3.7 0.4 1 0.5 0.8 0.6 0.7 1.5 1.1 0.7 0.9 0.4 1.3 0.7 3 0.6 1.7 0.7 1.1 0.8 0.6 5.4 2.9 0.7 0.5 1.6 1.4 2.5 3.4 0.4 1.4-0.2 1.2-1.2 2.3-0.9 2.2 0.1 1.2 0.8 0.8 2.9 1.4 0.4 0.9-0.2 0.9-0.6 0.7-0.7 0.6-0.6 2.2-0.2 3.7 0.7 15.9-0.2 1.2-2.4 6.1-0.8 3.4-0.1 1.8 0.2 1.4 6 9.8 2.1 4.6 0.6 0.7 0.8 0.4 1.1 0.2 1.2-0.1 2.1-0.5 3.9-1.3 2.2-0.3 1.4 0.2 1 0.3 2.8 1.2 1.3 6.3 1.1 1.6 0.9 0 1.6 0.1 2 0.5 3.7 1.5 1.3 1.3 0.3 1.1-1 1.7-1.8 2.2-0.5 0.8-0.6 0.7-2.9 5.2-1 2.3-1.2 3.6-17.9-11-25.5-11.3-27.6-6.6-12.8 0-9.8-2.3-53.3 0.5-22.8-5.8-8.1-1.5 1-2.5 4-9.1 2-3.2 9.5-8.4 1.6-1.9 1.1-1.5 0.5-4.7 0.7-3.2 0.8-1.9 0.9-1.2 0.8-0.8 0.7-0.5 8.2-8.2 1.9-2.6 1-1.8 0.1-1.3-0.5-8.5 0.6-4.5 1.1-4.6 0.8-2.1 0.8-1.4 0.8-0.7 1.5-0.9 0.9-0.2 2.6-0.3 1.5-0.6 1.5-1.2 4.4-5.5 2.1-1.9 5.5-3.2 2.4-1.7 2.7-2.4 1-1.5 0.5-1.5-0.2-1-0.3-1-0.5-0.8-0.7-0.6-0.8-0.6-0.9-0.4-4.3-1.4z" id="SVLI" name="La Libertad"></path>
    <path class="dept cursor-pointer" data-name="La Paz" data-unis="2" data-desc="UNIVO, UGB sede La Paz" d="M413 423.6l1.2-3.6 1-2.3 2.9-5.2 0.6-0.7 0.5-0.8 1.8-2.2 1-1.7-0.3-1.1-1.3-1.3-3.7-1.5-2-0.5-1.6-0.1-0.9 0-1.1-1.6-1.3-6.3 0.8-3.2 1.1-0.7 2.7-3 1.9-4.1 3.2-9.6 0.8-4.2 0.2-2.7-0.7-1.9-2.9-5.1-1.1-3.1-0.7-3.3 0.2-1.8 0.5-1.2 0.9-0.5 1-0.2 1.2 0 1.1 0.2 6 1.9 1.2 0.2 1.1 0.1 1.9-1.5 11.3-14.4 13.6-12.8 16.5 6.1 6.5 3.5 7.1 2.2 1.3 0 1.7-0.3 0.7-0.2 0.8-0.4 0.6-0.7 0.8-1.8 0.7-0.8 0.8-0.4 1-0.3 4.7-0.6 2.1-0.7 2.6-1.6 2.8 7.6 0.1 6.3-0.4 6.6 0.4 2.6 0.7 1.6 1.2 0.1 1.2 0 1.2-0.2 8.9-2.8 1.6-0.1 1.7 0.3 2.9 1.1 1 1.3 9.3 19.8 0.3 1.1 0.1 1.1-0.1 2.5-0.4 2.2-2.9 7.9-0.2 0.9-0.3 0.9-0.3 1.3 0 0.6 0 0.1-1.6 8.4 0 1.8 0.5 6.7 0.5 2.2 0.6 1.6 2.4 2.7 2.3 1.8 3.3 2 2.6 2.5 2.4 3.2 0.9 2 0.4 1.6-0.2 1.2-0.5 2.2-0.3 0.9-0.5 0.9-2.3 1.7-0.6 0.7-0.5 0.8-0.3 1-0.1 1.2-0.1 1.2 1.7 15.8-0.1 2.2-0.5 0.8-0.6 0.7-0.7 0.8-0.8 0.5-3.5 1.7-2.7 1.1-1.7 1-1.5 1.2-0.6 0.7-0.5 0.8-0.5 1-0.3 0.9-0.3 1.1-0.9 11.6 0 0.1-87.6-40.9-26.6-16.4z" id="SVPA" name="La Paz"></path>
    <path class="dept cursor-pointer" data-name="San Vicente" data-unis="2" data-desc="UES San Vicente, UNIVO" d="M520.2 293.8l0.5-11.1 0.7-1.2 1-1.4 2.4 0.1 2.2 0.5 3.9 1.3 2.6-0.4 3.6-1.2 11.7-5.2 2.5-0.6 2.7 0.6 2.2 0.2 1.3-0.8 1.2-1.3 1.9-2.5 1.4-0.7 1.2-0.1 5.4 3.9 1.8 0.5 2.7 0.2 12-0.1 2.4 0.4 5.2 0.3 4.1 0.8 0.9 0.4 6.9 5.1 0.9 0.5 3.9 1.4 2.6 1.5 0.9 0.4 2.6 0.7 1 0.5 0.6 0.9 0.4 2.1 0.3 1 0.5 0.8 0.7 0.6 1.4 0.5 9.5 1.2 3.5 0.9 6.1-4.1 1 9.1 11.6 11.6 4.3 6.8-23.6 17.2-2.1 6.8-5.6 7.7-7.5 4.8-8.3-1.9-10.8 13.4-0.5 2.4-6.2 1.1-2.1 3-0.9 11.7-3.2 11.5-17.1 32.6-1.6 4.1-0.9 4.9-0.5 12-0.9 2.6-6.6 6.4-3.5 5.2-2.2 4.2-3.6 2.9-7.3 1.1-4.5 1.7-2.8 3.8-1.5 5.5 0 0.8 0.2 0.1-5.6-2.6 0-0.1 0.9-11.6 0.3-1.1 0.3-0.9 0.5-1 0.5-0.8 0.6-0.7 1.5-1.2 1.7-1 2.7-1.1 3.5-1.7 0.8-0.5 0.7-0.8 0.6-0.7 0.5-0.8 0.1-2.2-1.7-15.8 0.1-1.2 0.1-1.2 0.3-1 0.5-0.8 0.6-0.7 2.3-1.7 0.5-0.9 0.3-0.9 0.5-2.2 0.2-1.2-0.4-1.6-0.9-2-2.4-3.2-2.6-2.5-3.3-2-2.3-1.8-2.4-2.7-0.6-1.6-0.5-2.2-0.5-6.7 0-1.8 1.6-8.4 0-0.1 0-0.6 0.3-1.3 0.3-0.9 0.2-0.9 2.9-7.9 0.4-2.2 0.1-2.5-0.1-1.1-0.3-1.1-9.3-19.8-1-1.3-2.9-1.1-1.7-0.3-1.6 0.1-8.9 2.8-1.2 0.2-1.2 0-1.2-0.1-0.7-1.6-0.4-2.6 0.4-6.6-0.1-6.3-2.8-7.6 7.3-7.2 0.8-1.3 0.9-1.8 1.3-5.4 6.9-18.3z" id="SVSV" name="San Vicente"></path>
    <path class="dept cursor-pointer" data-name="Usulután" data-unis="3" data-desc="UGB, UNIVO, Modular" d="M656.4 317.9l15.4-1.8 4.4 0.3 2.3 2.9 3.2 5 2.5 2.4 3.3 2.3 2.9 1 9.2 1.2 2.3 0.7 1.2 1 0 1.1-1.8 5-0.2 1.1 0 1.2 0.7 3.2 1.1 2.7 0.5 1.6 0.2 1.4-0.2 1-0.3 1-0.6 0.8-0.6 0.7-0.8 0.4-2.1 0.7-0.8 0.5-0.6 0.7-0.5 0.8-0.3 1-0.1 1.2 0 2.3 0.2 1.1 0.3 1 4.5 9.6 0.5 1.5 0 0.9-0.8 3 0 1.6 0.3 2 1.5 5.1 0.2 1.5-0.1 0.8-0.5 1-0.1 0.1-2 1.9-0.6 0.7-0.5 0.9-0.3 1-0.1 1.2 0.1 2.3-0.2 2.4-0.4 1-0.5 0.6-0.7 0-0.6-0.5-0.5-0.6-0.4-0.4-0.6-0.2-0.7 0.2-0.6 0.7-0.3 1-0.1 1.2 0.1 1.1 0.6 5.2 0.1 2.5 1.2 7.1 0.4 1.2 0.6 0.4 0.6 0.1 1.8-0.7 1.5 0.5 0.6 1 0.2 1.2-0.1 2.5 0.2 2.3 0.6 3 2 6.9 2 4.1 3.8 6.1 2.6 3.4 0.7 0.5 0.8 0.5 2.8 1.3 2 0.5 7.4 0.8 1.7 1 1.9 1.6 3.3 4 1.6 1.4 1.1 0.8 0.9-0.1 1.1 0.1 0.9 0.4 0.8 0.5 1.5 1.2 0.8 0.5 0.9 0.4 1.1 0.1 1 0 1.1-0.3 1.9-0.8 3.5-1.9 1.2-0.1 1.6 0.2 1.2 1.4 0.7 1.1 0.3 1.2 0.2 1.1 0 5.7 0.9 1.4 1.4 1.2 2.5 1.3 0.9 1.2 0.2 1.1-2.8 5.1-0.4 1.3-0.2 1.2 0.1 1.9 0.7 0.8 0.9 0.2 10.2-1.6 1.2 0.1 1 0.2 1 0.4 23 26.8-3.5 1.4-32.4 2.7-34.8-5.3-9 2.9-4.8-1.6-5.5-1.2 0-2.4 8.5-0.6 0.6-5.9-4.5-7.4-6.9-4.8 1.5 3 4.5 6.3 1.7 3.9-13.6-0.5-6.6 0.7-5.7 2.4 3.7 1.6 2.9-0.5 3.5-1 5.3-0.1 0 2.9-8.7 3.6-9.7-0.3-7.5-4.6-2.3-9.5 3.7 1.7 1.4 0.9 5.2-5.2 0-2.4-6.5-2.9-7-10-4.7-2.9 1.2 5.5 2.2 4.6 1.1 4.1-1.7 4-5.5-3.2-15.9-4-6.8-3.6-6.2-11.1-2.9-1.8-4.4-0.4-12.4-2.5-5.5 0-15.2 2.9-21.2 0.5-4.7 2.1 2.6 2 2.7 1.5 2.8 0.5 2.4-1.1 10.6 2.4 30.2-3.2 3.3 0.5 4.9 2.7 2.6 2.4 4.7 5.7 3.1 2.4 0 2.9-7-1.2-5.1-1-11.2-3.3 0 2.6 30.8 6.3 16.1 5.3 7.2 7.1-2.7 4.6-6.7-1.1-7.5-4-4.9-3.7-5.5-2.8-97-18.1-11.6-5.4-0.2-0.1 0-0.8 1.5-5.5 2.8-3.8 4.5-1.7 7.3-1.1 3.6-2.9 2.2-4.2 3.5-5.2 6.6-6.4 0.9-2.6 0.5-12 0.9-4.9 1.6-4.1 17.1-32.6 3.2-11.5 0.9-11.7 2.1-3 6.2-1.1 0.5-2.4 10.8-13.4 8.3 1.9 7.5-4.8 5.6-7.7 2.1-6.8 23.6-17.2z" id="SVUS" name="Usulután"></path>
    <path class="dept cursor-pointer" data-name="Cuscatlán" data-unis="2" data-desc="UNICO, UNIVO sede Cuscatlán" d="M410.9 171.4l6.6-3.4 1.2 0 1.3 0.1 4.7 2.4 9.7 3.5 1.9 1.1 2.7 2.4 6.3 4.5 0.6 0.8 1.7 3 8.2 9.8 0.7 1 0.2 0.7 0 0.1-0.3 1.6 0 1.1 0.1 0.9 0.3 1 0.6 1.8 1 2.1 2 2.9 1.7 1.6 1.4 0.9 8 3.8 6.8-0.5 5.8 2.2 6.2 0.7-5.5 8.8-0.4 0.9-1.1 1.6-1.3 1.4-1.4 1.2-5 2.9-0.8 0.6-0.6 0.6-0.6 0.9-0.3 0.9-0.3 1-0.2 1.2 0.9 2.5 1.8 3.3 7.3 10.4 2.3 2.4 3.1 2.1 1.8 2.1 1.4 3 0.3 1.7 0.4 1.6 1.9 3.4 0.5 1.5 0.5 2.5 0.9 1.7 1.5 2 3.6 3.9 2.4 3.7 0.4 1 3.4 1.9 13 3.6-6.9 18.3-1.3 5.4-0.9 1.8-0.8 1.3-7.3 7.2-2.6 1.6-2.1 0.7-4.7 0.6-1 0.3-0.8 0.4-0.7 0.8-0.8 1.8-0.6 0.7-0.8 0.4-0.7 0.2-1.7 0.3-1.3 0-7.1-2.2-6.5-3.5-16.5-6.1-0.3-11.6-0.8-2-0.7-2-1.8-2.3-6.6-12.5-0.5-1.8 0.6-8.3-0.3-1.7-0.7-1.1-1-0.3-1.4-1-1.6-1.6-4.7-7-0.7-0.6-1-0.4-1.1-0.2-2.4-0.1-2.6-2.1-3.4-4-10-15.7-1.1-3 0.7-0.6 2.7-1.2 1-0.7 0.7-0.8 0.7-1.5-0.4-0.8-2.3-3-7.8-12.8-3.5-7.4-0.8-2.9-0.4-3.3 0.2-0.6 0.6-0.5 0.7-0.6 0.7-0.6 1-1.6 0.3-0.9 0.3-1.1 0.3-2.3 0.5-2.1 0.2-1.6 0-0.4 0.1-0.7-0.3-3.7-1.2-6.4-0.1-1.7 0.3-1.2 1.8-3.7 1.7-4.6 0.2-3.8z" id="SVCU" name="Cuscatlán"></path>
    <path class="dept cursor-pointer" data-name="San Salvador" data-unis="14" data-desc="UES, UCA, UJMD, UFG, Tecnológica, UTEC y más" d="M363.6 177.2l2.7 0.4 1.5-0.8 4.9-3.5 2.9-1 7.8 3.4 5.2 0.1 2.4-4.8 5.3 4.2 4.2 1 10.4-4.8-0.2 3.8-1.7 4.6-1.8 3.7-0.3 1.2 0.1 1.7 1.2 6.4 0.3 3.7-0.1 0.7 0 0.4-0.2 1.6-0.5 2.1-0.3 2.3-0.3 1.1-0.3 0.9-1 1.6-0.7 0.6-0.7 0.6-0.6 0.5-0.2 0.6 0.4 3.3 0.8 2.9 3.5 7.4 7.8 12.8 2.3 3 0.4 0.8-0.7 1.5-0.7 0.8-1 0.7-2.7 1.2-0.7 0.6 1.1 3 10 15.7 3.4 4 2.6 2.1 2.4 0.1 1.1 0.2 1 0.4 0.7 0.6 4.7 7 1.6 1.6 1.4 1 1 0.3 0.7 1.1 0.3 1.7-0.6 8.3 0.5 1.8 6.6 12.5 1.8 2.3 0.7 2 0.8 2 0.3 11.6-13.6 12.8-11.3 14.4-1.9 1.5-1.1-0.1-1.2-0.2-6-1.9-1.1-0.2-1.2 0-1 0.2-0.9 0.5-0.5 1.2-0.2 1.8 0.7 3.3 1.1 3.1 2.9 5.1 0.7 1.9-0.2 2.7-0.8 4.2-3.2 9.6-1.9 4.1-2.7 3-1.1 0.7-0.8 3.2-2.8-1.2-1-0.3-1.4-0.2-2.2 0.3-3.9 1.3-2.1 0.5-1.2 0.1-1.1-0.2-0.8-0.4-0.6-0.7-2.1-4.6-6-9.8-0.2-1.4 0.1-1.8 0.8-3.4 2.4-6.1 0.2-1.2-0.7-15.9 0.2-3.7 0.6-2.2 0.7-0.6 0.6-0.7 0.2-0.9-0.4-0.9-2.9-1.4-0.8-0.8-0.1-1.2 0.9-2.2 1.2-2.3 0.2-1.2-0.4-1.4-2.5-3.4-1.6-1.4-0.7-0.5-5.4-2.9-0.8-0.6-0.7-1.1-0.6-1.7-0.7-3-0.4-1.3-0.7-0.9-1.5-1.1-0.6-0.7-0.5-0.8-0.4-1-0.3-3.7 0.4-3.8-1.9-2.5-1.2-0.1-1-0.3-0.6-0.8-0.2-1.5 1.7-4.8 2.6-4.9 0.3-1 0.3-1.6 0.4-6.2 0.7-3.6 1.2-2.8 0.8-3 0.7-6.8 0.5-1.8 0.7-1.9 1.2-1.4 0.7-0.6 0.9-0.5 1.9-0.7 0.8-0.5 0.4-0.9 0.5-2.2 1.9-21.6 0.4-1.2 0.7-0.7 1-0.3 2.8 0.1 0.3-0.3 0.1-0.4 0.5-2.2 1-2.6-0.1-0.6-0.6-0.8-2-1.1-1.4-1.1-1.2-0.7-1-0.4-1.1-0.2-1.1-0.2-1.2 0-3.5 0.3-1.1-0.1-3-0.9-1.8-0.9-1.6-1-1-1.4-1.1-2.1-1.9-4.4-1.1-2-0.9-1.3-2.3-1.8-0.2-1.4 0.1-2.1 2-8.3 0.4-13z" id="SVSS" name="San Salvador"></path>
 </g>
 <g id="points">
  <circle class="13.222973684790423|-89.99369975031595" cx="90.9" cy="497.5" id="0"></circle>
  <circle class="13.737667945092843|-88.78290699198428" cx="545.5" cy="298.8" id="1"></circle>
  <circle class="14.381035770470868|-87.81427278531893" cx="909.1" cy="49.8" id="2"></circle>
 </g>
 <g id="label_points">
  <circle class="Ahuachapán" cx="124.3" cy="249.3" id="SVAH"></circle>
  <circle class="Santa Ana" cx="251.3" cy="168.8" id="SVSA"></circle>
  <circle class="Chalatenango" cx="413.2" cy="117.2" id="SVCH"></circle>
  <circle class="Cabañas" cx="580.5" cy="233.6" id="SVCA"></circle>
  <circle class="Morazán" cx="808.4" cy="292.1" id="SVMO"></circle>
  <circle class="San Miguel" cx="753.2" cy="407.8" id="SVSM"></circle>
  <circle class="La Unión" cx="879.7" cy="380.1" id="SVUN"></circle>
  <circle class="Sonsonate" cx="208.7" cy="323.8" id="SVSO"></circle>
  <circle class="La Libertad" cx="325.4" cy="339.5" id="SVLI"></circle>
  <circle class="La Paz" cx="478.8" cy="400" id="SVPA"></circle>
  <circle class="San Vicente" cx="567.9" cy="326.5" id="SVSV"></circle>
  <circle class="Usulután" cx="635.8" cy="419.6" id="SVUS"></circle>
  <circle class="Cuscatlán" cx="446.2" cy="245.2" id="SVCU"></circle>
  <circle class="San Salvador" cx="406.6" cy="297.7" id="SVSS"></circle>
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

  <!-- LA RUTA DEL ESTUDIANTE (Interactive Roadmap Stepper) -->
  <section class="roadmap-section" id="roadmap">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-tag">Guía de Navegación</span>
        <h2>La ruta hacia tu universidad</h2>
        <p>Conoce los 5 pasos esenciales para asegurar tu beca en El Salvador.</p>
      </div>

      <div class="roadmap-steps-container" data-reveal>
        <div class="roadmap-step-item active" data-step="1">
          <div class="step-num-badge">1</div>
          <h4 style="color:#fff; font-size:0.95rem;">Test Socioemocional</h4>
          <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.3rem;">Diagnóstico de fortalezas</p>
        </div>

        <div class="roadmap-step-item" data-step="2">
          <div class="step-num-badge">2</div>
          <h4 style="color:#fff; font-size:0.95rem;">Exploración del Mapa</h4>
          <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.3rem;">Búsqueda por departamento</p>
        </div>

        <div class="roadmap-step-item" data-step="3">
          <div class="step-num-badge">3</div>
          <h4 style="color:#fff; font-size:0.95rem;">Agenda & Alertas</h4>
          <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.3rem;">Control de fechas límite</p>
        </div>

        <div class="roadmap-step-item" data-step="4">
          <div class="step-num-badge">4</div>
          <h4 style="color:#fff; font-size:0.95rem;">Red de Padrinos</h4>
          <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.3rem;">Patrocinio transparente</p>
        </div>

        <div class="roadmap-step-item" data-step="5">
          <div class="step-num-badge">5</div>
          <h4 style="color:#fff; font-size:0.95rem;">Zarpar a la Universidad</h4>
          <p style="font-size:0.78rem; color:var(--text-2); margin-top:0.3rem;">¡Éxito académico!</p>
        </div>
      </div>

      <div class="roadmap-preview-box" id="roadmapPreviewBox" data-reveal>
        <div>
          <span id="stepTag" style="color:var(--teal); font-size:0.8rem; font-weight:800; text-transform:uppercase;">Paso 1 de 5</span>
          <h3 id="stepTitle" style="font-size:1.4rem; margin-top:0.3rem; color:#fff;">Test Socioemocional & Orientación Vocacional</h3>
          <p id="stepDesc" style="color:var(--text-2); margin-top:0.5rem; max-width:600px;">
            Inicias identificando tus inteligencias múltiples y rasgos socioemocionales. Esto te permite elegir la carrera y universidad con mayor proyección para ti.
          </p>
        </div>
        <a href="#" id="stepBtn" class="btn-primary" style="white-space:nowrap;">Hacer el Test Gratis →</a>
      </div>
    </div>
  </section>

  <!-- TICKER CONTINUO DE ALIADOS & INSTITUCIONES -->
  <style>
    .aliados-ticker-wrapper {
      overflow: hidden;
      white-space: nowrap;
      position: relative;
      width: 100%;
      padding: 2.5rem 0;
      mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
      -webkit-mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
    }
    
    .ticker-track {
      display: inline-flex;
      align-items: center;
      gap: 3rem; /* uniform spacing between logos */
      animation: tickerScroll 35s linear infinite;
      width: max-content;
    }
    
    .ticker-track:hover {
      animation-play-state: paused;
    }
    
    .ticker-logo-box {
      background: white;
      border-radius: 16px;
      padding: 0.75rem 1.25rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 110px;
      width: 240px;
      cursor: pointer;
    }
    
    .ticker-logo-box:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 8px 25px rgba(45, 212, 191, 0.4);
    }
    
    .ticker-logo-box img {
      max-height: 100%;
      max-width: 100%;
      object-fit: contain;
    }
  </style>

  <section class="section-aliados">
    <div class="container" style="margin-bottom:2rem; text-align:center;" data-reveal>
      <span class="section-tag">Nuestra Red de Aliados</span>
      <h3 style="color:var(--text-2); font-weight:600; font-size:1.1rem; text-transform:uppercase; letter-spacing:0.1em; margin-top:0.5rem;">Universidades e Instituciones a Bordo</h3>
    </div>
    <div class="aliados-ticker-wrapper">
      <div class="ticker-track">
        <!-- Original set -->
        <div class="ticker-logo-box"><img src="{{ asset('media/ues.png') }}" alt="UES"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/uca.jpg') }}" alt="UCA"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/udb.png') }}" alt="UDB"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/utec.jpg') }}" alt="UTEC"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/UGB.png') }}" alt="UGB"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/josematias.png') }}" alt="UJMD"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/univo.png') }}" alt="UNIVO"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/unasa.png') }}" alt="UNASA"></div>
        
        <!-- Duplicated set for infinite loop -->
        <div class="ticker-logo-box"><img src="{{ asset('media/ues.png') }}" alt="UES"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/uca.jpg') }}" alt="UCA"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/udb.png') }}" alt="UDB"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/utec.jpg') }}" alt="UTEC"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/UGB.png') }}" alt="UGB"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/josematias.png') }}" alt="UJMD"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/univo.png') }}" alt="UNIVO"></div>
        <div class="ticker-logo-box"><img src="{{ asset('media/unasa.png') }}" alt="UNASA"></div>
      </div>
    </div>
  </section>

  <!-- BITÁCORA DE NAVEGANTES (Testimonios Slider) -->
  <section class="section-testimonios" id="testimonios">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-tag">Bitácora de Navegantes</span>
        <h2>Estudiantes que ya alcanzaron la orilla</h2>
        <p>Historias reales de jóvenes salvadoreños que transformaron su futuro con una beca universitaria.</p>
      </div>

      <div class="testimonios-grid">
        
        <div class="bitacora-card" data-reveal>
          <div class="captain-info">
            <div class="captain-avatar-box">
              <img src="https://rree.gob.sv/wp-content/uploads/2026/01/WhatsApp-Image-2026-01-14-at-1.55.30-PM-1024x682.jpeg" alt="Jose Alejandro Hernández Grijalva" class="captain-avatar">
            </div>
            <div>
              <h4 class="captain-name">José Alejandro Hernández Grijalva</h4>
              <span class="captain-univ">Becario del Programa Agrobecas (Universidad Zamorano)</span>
            </div>
          </div>
          <p class="bitacora-quote">""Estoy profundamente agradecido con el Ministerio de Relaciones Exteriores y la Universidad Zamorano por esta oportunidad. Mi mayor motivación para estudiar agricultura es poder contribuir al desarrollo de mi familia y de mi país a través de la innovación, aprovechando la tierra para obtener los mejores frutos. Trabajar en el campo es un privilegio porque se trata con vida; los agrónomos y agricultores somos quienes alimentamos a la nación.""</p>
          <span class="bitacora-badge">⭐ Becado 100% — Ahuachapán</span>
        </div>

        <div class="bitacora-card" data-reveal>
          <div class="captain-info">
            <div class="captain-avatar-box">
              <img src="https://rree.gob.sv/wp-content/uploads/2026/01/WhatsApp-Image-2026-01-14-at-1.55.31-PM-1023x1536.jpeg" alt="Emeris Adilene Castillo Guevara" class="captain-avatar">
            </div>
            <div>
              <h4 class="captain-name">Emeris Adilene Castillo Guevara</h4>
              <span class="captain-univ">Becaria del Programa Agrobecas (Universidad Zamorano)</span>
            </div>
          </div>
          <p class="bitacora-quote">""Formar parte del Programa Agrobecas representa un paso decisivo hacia mi futuro profesional en el sector agrícola. Agradezco las gestiones para hacer posible que los jóvenes salvadoreños accedamos a una educación superior de excelencia en la Universidad Zamorano, comprometiéndome a aplicar cada conocimiento adquirido en favor del desarrollo y la seguridad alimentaria de nuestro país."</p>
          <span class="bitacora-badge">⭐ Becaria Nacional — San Salvador</span>
        </div>

        <div class="bitacora-card" data-reveal>
          <div class="captain-info">
            <div class="captain-avatar-box">
              <img src="https://rree.gob.sv/wp-content/uploads/2025/07/WhatsApp-Image-2025-07-30-at-1.25.47-PM-1024x628.jpeg" alt="Jovenes" class="captain-avatar">
            </div>
            <div>
              <h4 class="captain-name">Programa de Agrobecas – Jóvenes Salvadoreños</h4>
              <span class="captain-univ">Programa Agrobecas (Cancillería / ESCO / SETEFE)</span>
            </div>
          </div>
          <p class="bitacora-quote">""Ser parte de esta iniciativa educativa nos compromete a dar nuestro máximo esfuerzo en la Universidad Zamorano. Este programa no solo abre las puertas a una formación profesional de excelencia, sino que nos motiva a regresar capacitados para responder a los desafíos agrícolas del país y transformar el desarrollo territorial de nuestras comunidades."</p>
          <span class="bitacora-badge">⭐ (Cancillería / ESCO / SETEFE) — El Salvador</span>
        </div>

      </div>
    </div>
  </section>

  <!-- MODAL MAPA -->
  <div id="uniModal" class="modal">
    <div class="modal-content">
        <span id="closeModal">&times;</span>
        <h2 id="modalDept"></h2>
        <div id="universidadesContainer"></div>
    </div>
  </div>


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
        <style>
          @keyframes pulseRed {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.9); }
            50% { transform: scale(1.1); box-shadow: 0 0 10px 6px rgba(255, 0, 0, 0.6); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 0, 0, 0); }
          }
          .blinking-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #ff1c1c;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulseRed 1s infinite ease-in-out;
            box-shadow: 0 0 8px rgba(255, 0, 0, 0.8);
          }
        </style>
        <p style="margin-top: 1rem; font-size: 0.75rem; color: rgba(255,255,255,0.5); line-height: 1.5; max-width: 350px; display: flex; align-items: flex-start;">
          <span style="margin-top: 4px;"><span class="blinking-dot"></span></span>
          <span><strong>Aviso:</strong> Este sitio web es un proyecto estudiantil desarrollado con fines estrictamente académicos. Las marcas, logotipos e imágenes universitarias utilizadas pertenecen a sus respectivos propietarios y se muestran únicamente con propósito ilustrativo e informativo, sin fines de lucro ni vinculación oficial.</span>
        </p>
      </div>
      <div class="footer-links">
        <div>
          <h5>Plataforma</h5>
          <a href="#">Test Socioemocional</a>
          <a href="#universidades">Mapa de Universidades</a>
          <a href="{{ route('becas.calendario') }}">Calendario de Becas</a>
        </div>
        <div>
          <h5>Comunidad</h5>
          <a href="#hub-social">Hub Social</a>
          <a href="#">Padrinos</a>
          <a href="#testimonios">Testimonios</a>
        </div>
        <div>
          <h5>Empresa</h5>
          <a href="#nosotros">Sobre nosotros</a>
          <a href="#">Contacto</a>
          <a href="#">Privacidad</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 UGF. Navegando hacia tu futuro.</p>
    </div>
  </footer>
  
  <dialog id="departmentModal" class="hidden backdrop:bg-black/80 p-6 rounded-2xl max-w-2xl w-full bg-[#071f35] border border-blue-500/30 text-white flex-col outline-none fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[999]">
    <div class="flex justify-between items-center border-b border-blue-500/20 pb-4 mb-4">
      <h3 id="modalDeptName" class="text-2xl font-bold text-yellow-400"></h3>
      <button onclick="window.closeModal()" class="text-gray-400 hover:text-white text-2xl font-bold cursor-pointer" style="background:none; border:none; outline:none;">&times;</button>
    </div>
    
    <p id="modalDeptDesc" class="text-gray-300 mb-4"></p>

    <div class="overflow-y-auto max-h-[60vh] pr-2">
      <h4 class="text-lg font-semibold text-teal-400 mb-3">Universidades con Becas:</h4>
      <div id="modalUniversitiesList" class="space-y-6"></div>
    </div>
  </dialog>

  <!-- BRÚJULA FLOTANTE DE NAVEGACIÓN -->
  <div class="floating-compass-widget" id="floatingCompass" title="Brújula de Navegación">
    <div class="compass-needle" id="compassNeedle"></div>
  </div>

  <!-- BARRA FLOTANTE DE ACCESOS RÁPIDOS (Marine Dock) -->
  <nav class="floating-marine-dock" id="marineDock">
    <a href="#inicio" class="dock-item active">⛵ Inicio</a>
    <a href="#servicios" class="dock-item">🌊 Servicios</a>
    <a href="#becas" class="dock-item">🎓 Becas</a>
    <a href="#hub-social" class="dock-item">💬 Hub Social</a>
    <a href="#universidades" class="dock-item">🗺️ Mapa</a>
    <a href="#roadmap" class="dock-item">🧭 La Ruta</a>
    <a href="#testimonios" class="dock-item">⚓ Bitácora</a>
    <a href="{{ route('becas.calendario') }}" class="dock-item">📅 Calendario</a>
  </nav>

  @include('components.chatbot')
</body>

</html>
