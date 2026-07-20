<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro Estudiantil - UGF</title>

    <!-- Google Fonts matching Figma Design -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@400;700&family=Nunito:wght@300;400;600;700&family=Spline+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Stylesheet -->
    @vite(['resources/css/foro/index.css']) <!-- Fallback stylesheet link for local preview without Laravel server running -->
    <link rel="stylesheet" href="foro.css">
</head>

<body>

    <!-- Header Section (Inspired by Figma Banner & Wave) -->
    <header class="foro-header">
        <div class="header-waves">
            <!-- SVG Waves mimicking 'ondaOla' for premium feel -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#0059ff" fill-opacity="1"
                    d="M0,192L48,181.3C96,171,192,149,288,144C384,139,480,149,576,176C672,203,768,245,864,245.3C960,245,1056,203,1152,176C1248,149,1344,139,1392,133.3L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
                </path>
            </svg>
        </div>

        <div class="container header-container">
            <!-- Brand Logo (Group 38 in Figma) -->
            <div class="brand-logo">
                <div class="logo-circle"></div>
                <span class="logo-text">UGF</span>
            </div>

            <!-- Header Titles -->
            <div class="header-titles">
                <span class="subtitle">Explora, comparte, aprende</span>
                <h1 class="main-title">Foro estudiantil</h1>
            </div>

            <!-- Ship Icon Container (Barquito in Figma) -->
            <div class="ship-decoration">
                <svg viewBox="0 0 100 100" class="ship-svg">
                    <!-- Premium minimal sailboat SVG matching 'Barquito' theme -->
                    <path d="M20 70 L80 70 L70 85 L30 85 Z" fill="#ffffff" />
                    <path d="M48 20 L48 65 L25 65 Z" fill="#ffc300" />
                    <path d="M52 15 L52 65 L75 65 Z" fill="#ffffff" fill-opacity="0.8" />
                    <line x1="50" y1="10" x2="50" y2="70" stroke="#ffffff"
                        stroke-width="2" />
                </svg>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="foro-main">
        <div class="container main-container">

            <!-- Left Side: Active Thread Detail (Rectangle 26 in Figma) -->
            <section class="active-thread-container">
                <div class="thread-card">
                    <!-- Dynamic check for Laravel -->
                    @if (isset($activeThread))
                        <div class="thread-header">
                            <span class="thread-meta">Publicado por {{ $activeThread->author ?? 'Usuario' }} •
                                {{ $activeThread->created_at->diffForHumans() ?? '' }}</span>
                            <h2 class="thread-title">{{ $activeThread->title }}</h2>
                        </div>
                        <div class="thread-body">
                            <p>{{ $activeThread->content }}</p>
                        </div>
                    @else
                        <!-- Demo Content matching Figma exactly -->
                        <div class="thread-header">
                            <span class="thread-meta">Publicado por @EstudianteUGF • Hace 2 horas</span>
                            <h2 class="thread-title">¿Qué debo saber antes de iniciar la universidad?</h2>
                        </div>
                        <div class="thread-body">
                            <p>Resulta que estoy muy entusiasmado pero no sé cómo será el ambiente, la carga de materias
                                o el ritmo de estudio.</p>
                            <p>Me gustaría saber sus experiencias de primer ingreso en la Universidad Don Bosco (UDB).
                                ¿Qué herramientas son esenciales? ¿Cuáles son los mejores hábitos para no morir en el
                                intento? ¡Cualquier consejo es bienvenido!</p>
                        </div>
                    @endif

                    <!-- Premium Interaction Zone (Like, replies, and comments) -->
                    <div class="thread-footer">
                        <div class="interaction-buttons">
                            <button class="btn-action">
                                <span class="icon">♥</span> 24 Likes
                            </button>
                            <button class="btn-action">
                                <span class="icon">💬</span> 12 Comentarios
                            </button>
                        </div>
                    </div>

                    <!-- Comment Section (Laravel Dynamic Loop Placeholder) -->
                    <div class="comments-section">
                        <h3>Comentarios</h3>
                        <div class="comment-item">
                            <div class="comment-avatar">U</div>
                            <div class="comment-content">
                                <strong>Usuario123</strong>
                                <p>Te recomiendo llevar siempre una agenda o usar Notion para organizar tus tareas desde
                                    el día uno. ¡Muchos éxitos!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Right Side: Sidebar Forum Threads (Rectangle 35, 53, etc.) -->
            <aside class="threads-sidebar">
                <h2 class="sidebar-title">Todos los foros</h2>

                <div class="threads-list">
                    <!-- Dynamic Loop for Laravel -->
                    @if (isset($threads))
                        @foreach ($threads as $t)
                            <a href="{{ route('foro.show', $t->id) }}"
                                class="thread-link-card {{ isset($activeThread) && $activeThread->id == $t->id ? 'active' : '' }}">
                                <span class="thread-card-title">{{ $t->title }}</span>
                            </a>
                        @endforeach
                    @else
                        <!-- Demo List matching Figma exactly -->
                        <a href="#" class="thread-link-card active">
                            <span class="thread-card-title">¿Qué debo saber antes de iniciar la universidad?</span>
                        </a>
                        <a href="#" class="thread-link-card">
                            <span class="thread-card-title">¿Cuál es la mejor universidad?</span>
                        </a>
                        <a href="#" class="thread-link-card">
                            <span class="thread-card-title">¿Qué necesito para obtener una beca?</span>
                        </a>
                        <a href="#" class="thread-link-card">
                            <span class="thread-card-title">¿Algún consejo para un adolescente que deja el
                                bachillerato?</span>
                        </a>
                        <a href="#" class="thread-link-card">
                            <span class="thread-card-title">¿Cómo puedo mejorar mis notas?</span>
                        </a>
                        <a href="#" class="thread-link-card">
                            <span class="thread-card-title">Convocatoria de Becas UGF 2026</span>
                        </a>
                    @endif
                </div>
            </aside>

        </div>
    </main>

    <!-- Footer decoration (ondaOla at the bottom) -->
    <footer class="foro-footer">
        <div class="footer-waves">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#0059ff" fill-opacity="1"
                    d="M0,224L48,202.7C96,181,192,139,288,144C384,149,480,203,576,218.7C672,235,768,213,864,186.7C960,160,1056,128,1152,122.7C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
        <div class="container footer-content">
            <p>&copy; {{ date('Y') }} UGF - Mar de Oportunidades. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>

</html>
