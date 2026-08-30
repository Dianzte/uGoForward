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
              <li><a href="{{ route('index') }}">Inicio</a></li>
              <li><a href="{{ route('becas.index') }}">Becas</a></li>
              <li><a href="{{ route('foro.index') }}">Foro estudiantil</a></li>
              <li>
                  <a href="{{ route('hub.feed') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#7C3AED,#4F46E5);color:white;padding:5px 14px;border-radius:20px;font-weight:600;font-size:13px;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.opacity='0.85';this.style.transform='translateY(-1px)'" onmouseout="this.style.opacity='1';this.style.transform='translateY(0)'">
                      🎓 Student Hub
                  </a>
              </li>
          </ul>
          <div class="nav-actions">
              @auth
                  <a href="{{ route('hub.feed') }}" class="btn-ghost" style="display:inline-flex;align-items:center;gap:5px;">🎓 Mi Hub</a>
              @else
                  <a href="{{ route('registro') }}" class="btn-ghost">Registrarse</a>
                  <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
              @endauth

              {{-- ══ BOTÓN DARK/LIGHT MODE ══ --}}
              <button
                  id="theme-toggle-btn"
                  class="theme-toggle"
                  aria-label="Cambiar tema"
                  title="Cambiar entre modo claro y oscuro"
              >
                  {{-- Sol (visible en modo oscuro) --}}
                  <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="12" r="5"/>
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                  </svg>
                  {{-- Luna (visible en modo claro) --}}
                  <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
                  </svg>
              </button>
          </div>
          <button class="burger" id="burger" aria-label="Menú">
              <span></span><span></span><span></span>
          </button>
      </div>
      <div class="mobile-menu" id="mobileMenu">
          <a href="{{ route('index') }}#servicios">Servicios</a>
          <a href="{{ route('index') }}#universidades">Universidades</a>
          <a href="{{ route('index') }}#nosotros">Nosotros</a>
          <a href="{{ route('hub.feed') }}" style="text-align:center;display:block;background:linear-gradient(135deg,#7C3AED,#4F46E5);color:white;padding:10px;border-radius:8px;font-weight:600;text-decoration:none;margin-bottom:4px;">🎓 Student Hub</a>
          @auth
              <span class="btn-ghost" style="text-align:center;display:block;opacity:0.7;">{{ Auth::user()->nombre }}</span>
          @else
              <a href="{{ route('registro') }}" class="btn-ghost" style="text-align: center;">Registrarse</a>
              <a href="{{ route('login') }}" class="btn-primary" style="text-align: center;">Iniciar sesión</a>
          @endauth
          {{-- Toggle en menú mobile --}}
          <button id="theme-toggle-mobile" class="theme-toggle" aria-label="Cambiar tema" style="margin-top: 0.5rem;">
              <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="5"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
              </svg>
              <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
              </svg>
          </button>
      </div>
  </nav>

  <script>
  (function () {
      'use strict';

      // ── Utilidades de tema ─────────────────────────────────────────────
      function applyTheme(isDark) {
          var html = document.documentElement;
          if (isDark) {
              html.classList.add('dark');
              localStorage.setItem('theme', 'dark');
          } else {
              html.classList.remove('dark');
              localStorage.setItem('theme', 'light');
          }
          // Notificar al chatbot si existe
          if (typeof window.guayabotUpdateTheme === 'function') {
              window.guayabotUpdateTheme(isDark);
          }
      }

      function isDarkMode() {
          return document.documentElement.classList.contains('dark');
      }

      function toggleTheme() {
          applyTheme(!isDarkMode());
      }

      // ── Burger menu ────────────────────────────────────────────────────
      document.addEventListener('DOMContentLoaded', function () {
          var burger  = document.getElementById('burger');
          var mobile  = document.getElementById('mobileMenu');
          var toggleBtn   = document.getElementById('theme-toggle-btn');
          var toggleMobile = document.getElementById('theme-toggle-mobile');

          // Toggle de tema (desktop)
          if (toggleBtn) {
              toggleBtn.addEventListener('click', toggleTheme);
          }
          // Toggle de tema (mobile)
          if (toggleMobile) {
              toggleMobile.addEventListener('click', toggleTheme);
          }

          // Burger / menú mobile
          if (burger && mobile) {
              burger.addEventListener('click', function () {
                  mobile.classList.toggle('open');
              });
          }

          // Scroll: navbar scrolled class
          window.addEventListener('scroll', function () {
              var navbar = document.getElementById('navbar');
              if (navbar) {
                  navbar.classList.toggle('scrolled', window.scrollY > 10);
              }
          }, { passive: true });
      });

      // ── Escuchar cambios de preferencia del sistema en tiempo real ─────
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
          if (!localStorage.getItem('theme')) {
              applyTheme(e.matches);
          }
      });
  }());
  </script>
