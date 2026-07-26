  @vite(['resources/css/navbar.css'])
  <nav id="navbar">
      <div class="nav-inner">
          <a class="nav-logo" href="#">
              <span>UGF</span>
          </a>
          <ul class="nav-links">
              <li><a href="#servicios">Servicios</a></li>
              <li><a href="#universidades">Universidades</a></li>
              <li><a href="#nosotros">Nosotros</a></li>
          </ul>
          <div class="nav-actions">
              <a href="#" class="btn-ghost">Registrarse</a>
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
