  @vite(['resources/css/navbar.css'])
  <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />

  <nav id="navbar">
      <div class="nav-inner">
          <a class="nav-logo" href="#">
              <span>UGF</span>
          </a>
          <ul class="nav-links">
              <li><a href="{{ route('index') }}">Inicio</a></li>
              <li><a href="{{ route('becas.index') }}">Becas</a></li>
              <li><a href="{{ route('foro.index') }}">Foro estudiantil</a></li>
          </ul>
          <div class="nav-actions">
              <a href="" class="btn-ghost">Registrarse</a>
              <a href="#" class="btn-primary">Iniciar sesión</a>
          </div>
          <button class="burger" id="burger" aria-label="Menú">
              <span></span><span></span><span></span>
          </button>
      </div>
      <div class="mobile-menu" id="mobileMenu">
          <a href="#servicios">Servicios</a>
          <a href="#universidades">Universidades</a>
          <a href="#nosotros">Nosotros</a>
          <a href="#" class="btn-primary">Iniciar sesión</a>
      </div>
  </nav>
