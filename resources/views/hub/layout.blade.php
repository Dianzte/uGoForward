<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Student Hub' }} · uGoForward</title>
    <meta name="description" content="Plataforma estudiantil colaborativa de uGoForward. Comparte recursos, chatea y alcanza tus metas.">

    {{-- Anti-FOUC: aplicar tema oscuro antes de render --}}
    <script>document.documentElement.classList.add('dark');</script>

    @vite(['resources/css/app.css', 'resources/css/hub/hub.css'])
</head>
<body style="background:#080B14; margin:0; padding:0;">

<div class="hub-root">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="hub-sidebar" id="hubSidebar">

        {{-- Logo --}}
        <div class="hub-sidebar-logo">
            <div class="hub-sidebar-logo-icon">🎓</div>
            <div>
                <div class="hub-sidebar-logo-text">Student Hub</div>
                <div class="hub-sidebar-logo-sub">uGoForward</div>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="hub-nav">
            <div class="hub-nav-section">Módulos</div>

            <a href="{{ route('hub.feed') }}"
               class="hub-nav-item {{ request()->routeIs('hub.feed') ? 'active' : '' }}">
                <svg class="hub-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Muro Académico
            </a>

            <a href="{{ route('hub.chat') }}"
               class="hub-nav-item {{ request()->routeIs('hub.chat*') ? 'active' : '' }}">
                <svg class="hub-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Chat Estudiantil
                <span class="hub-nav-badge" id="chatBadge" style="display:none">●</span>
            </a>

            <a href="{{ route('hub.goals') }}"
               class="hub-nav-item {{ request()->routeIs('hub.goals*') ? 'active' : '' }}">
                <svg class="hub-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Mis Metas
            </a>

            <div class="hub-nav-section" style="margin-top:8px;">Plataforma</div>

            <a href="{{ route('foro.index') }}" class="hub-nav-item">
                <svg class="hub-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
                Foro
            </a>

            <a href="{{ route('becas.index') }}" class="hub-nav-item">
                <svg class="hub-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
                Becas
            </a>

            <a href="{{ route('index') }}" class="hub-nav-item">
                <svg class="hub-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Inicio
            </a>
        </nav>

        {{-- Usuario activo --}}
        <div class="hub-sidebar-user">
            <div class="hub-sidebar-avatar">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->nombre }}">
                @else
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                @endif
            </div>
            <div class="hub-sidebar-user-info">
                <div class="hub-sidebar-user-name">{{ Auth::user()->nombre }}</div>
                <div class="hub-sidebar-user-role">Estudiante</div>
            </div>
        </div>
    </aside>

    {{-- ══ ÁREA PRINCIPAL ══ --}}
    <div class="hub-main">

        {{-- Header --}}
        <header class="hub-header">
            {{-- Hamburguesa (mobile) --}}
            <button class="hub-btn-icon" id="sidebarToggle" style="display:none" aria-label="Menú">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="hub-header-title">{{ $title ?? 'Student Hub' }}</h1>

            {{-- Búsqueda --}}
            <form method="GET" action="{{ route('hub.feed') }}" class="hub-header-search">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--hub-text-muted);flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" placeholder="Buscar aportes..."
                       value="{{ request('q') }}">
            </form>

            {{-- Acciones del header --}}
            <div class="hub-header-actions">
                <a href="{{ route('hub.chat') }}" class="hub-btn-icon" title="Chat">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </a>
                <a href="{{ route('hub.goals') }}" class="hub-btn-icon" title="Mis metas">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </a>
            </div>
        </header>

        {{-- Contenido --}}
        <main class="hub-content">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="hub-alert hub-alert-success">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="hub-alert hub-alert-error">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

<script>
    // ── Sidebar toggle mobile ──
    const sidebar = document.getElementById('hubSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    if (window.innerWidth <= 768) {
        toggleBtn.style.display = 'flex';
    }

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
    });

    // Cerrar sidebar al hacer click fuera
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !toggleBtn?.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
        }
    });

    // ── Auto-dismiss flash messages ──
    setTimeout(() => {
        document.querySelectorAll('.hub-alert').forEach(el => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>

</body>
</html>
