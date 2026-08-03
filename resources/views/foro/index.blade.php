<x-foro-layout>
    @push('estilo')
    @vite(['resources/css/foro/index.css'])
    @endpush
    <header class="foro-header">


        <div class="header-titles">
            <span class="subtitle">Explora, comparte, aprende</span>
            <h1 class="main-title">Foro estudiantil</h1>
        </div>


    </header>

    <main class="foro-main">
        <div class="container main-container">

            <section class="active-thread-container">
                <div class="thread-card">
                    @if (isset($ejemplo))
                        <div class="thread-header">
                            <span class="thread-meta">Publicado por  • {{ auth()->user()->usuario }}
                                {{ $ejemplo->created_at->diffForHumans() ?? '' }}</span>
                            <h2 class="thread-title">{{ $ejemplo->titulo }}</h2>
                        </div>
                        <div class="thread-body">
                            <p>{{ $ejemplo->contenido }}</p>
                        </div>
                    @else
                        <div class="thread-header">
                            <span class="thread-meta">Publicado por @equipoUGF</span>
                            <h2 class="thread-title">Bienvenido</h2>
                        </div>
                        <div class="thread-body">
                            <p>Comparte tus preguntas y tus conocimientos.</p>

                        </div>
                    @endif
                    @if (isset($ejemplo))
                        <div class="thread-footer">
                            <div class="interaction-buttons">
                                <button class="btn-action">
                                    <span class="icon">👁️</span> {{ $ejemplo->visitas_count }}
                                </button>
                                <button class="btn-action" title="Denunciar">
                                    <span class="icon">🏴</span> {{ $ejemplo->reportes_count }}
                                </button>
                            </div>
                        </div>

                        <div class="comments-section">
                            <div class="comments-header">
                                <h3 class="comments-title">
                                    <span class="comments-icon">💬</span> Comentarios
                                    <span class="comments-count-badge">{{ $ejemplo->comentarios->count() }}</span>
                                </h3>
                            </div>

                            <form class="comment-form" action="{{ route('comentario.store', $ejemplo) }}"
                                method="POST">
                                @csrf

                                <div class="comment-form-wrapper">
                                    <div class="comment-avatar user-avatar" style="{{ auth()->user()->avatarImg ? 'background-image: url(' . asset('storage/' . auth()->user()->avatarImg->ruta) . ')' : '' }}"></div>
                                    <div class="comment-input-group">
                                        <textarea name="contenido" class="comment-textarea" placeholder="Escribe un comentario o respuesta..." rows="3"
                                            required></textarea>
                                        <div class="comment-form-actions">
                                            <button type="submit" class="btn-submit-comment">
                                                <span>Publicar comentario</span>
                                                <span class="send-icon">➔</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <div class="comments-list">

                                @forelse ($ejemplo->comentarios as $comentario)
                                    @if ($comentario->padre_id == null)
                                        <div class="comment-item">
                                            <div class="comment-avatar" style="{{ $comentario->comentarista?->avatarImg ? 'background-image: url(' . asset('storage/' . $comentario->comentarista?->avatarImg->ruta) . ')' : '' }}"></div>
                                            <div class="comment-content">
                                                <div class="comment-meta">
                                                    <strong class="comment-author">{{ $comentario->comentarista?->nombre ?? $comentario->comentarista?->usuario ?? 'Anónimo' }}</strong>
                                                    <span
                                                        class="comment-date">{{ $comentario->created_at ? $comentario->created_at->diffForHumans() : 'Hace unas horas' }}</span>
                                                </div>
                                                <p class="comment-text">{{ $comentario->contenido }}</p>
                                                <div class="comment-actions">
                                                    <button class="btn-comment-action"><span class="icon">♥</span>
                                                        {{ $comentario->likes_count ?? 0 }}</button>
                                                    <button class="btn-comment-action"
                                                        onclick="toggleForm('form-respuesta-{{ $comentario->id }}')"><span
                                                            class="icon">↩</span>
                                                        Responder</button>
                                                </div>

                                                <form id="form-respuesta-{{ $comentario->id }}"
                                                    action="{{ route('comentario.store', $ejemplo) }}" method="POST"
                                                    style="display: none; margin-top: 10px;">
                                                    @csrf
                                                    <input type="hidden" name="padre_id"
                                                        value="{{ $comentario->id }}">

                                                    <textarea name="contenido" rows="2" required style="width: 100%; margin-bottom: 5px;"></textarea>
                                                    <button type="submit"
                                                        style="background: #2563eb; color: white; padding: 4px 10px; border-radius: 4px; border: none;">Publicar
                                                        respuesta</button>
                                                </form>

                                                @if ($comentario->respuestas->count() > 0)
                                                    <div class="respuestas-anidadas"
                                                        style="margin-left: 30px; border-left: 3px solid #e5e7eb; padding-left: 15px; margin-top: 15px;">
                                                        @foreach ($comentario->respuestas as $respuesta)
                                                            <div class="respuesta-item" style="margin-bottom: 10px;">
                                                                <strong>{{ $respuesta->comentarista?->nombre ?? $respuesta->comentarista?->usuario ?? 'Anónimo' }}</strong>
                                                                <small>{{ $respuesta->created_at->diffForHumans() }}</small>
                                                                <p style="margin: 3px 0;">{{ $respuesta->contenido }}
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <p class="comment-text">Aún no hay respuestas en esta publicación</p>
                                @endforelse

                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <aside class="threads-sidebar">
                <h2 class="sidebar-title">Todos los foros</h2>

                <div class="threads-list">
                    @forelse ($foros as $foro)
                        <a href="{{ route('foro.show', $foro->slug) }}" class="thread-link-card ">
                            <span class="thread-card-title">{{ $foro->titulo }}</span>
                        </a>
                    @empty
                    @endforelse

                    <a href="{{ route('foro.crear') }}" class="thread-link-card active">
                        <span class="thread-card-title">Crear un foro</span>
                    </a>

                </div>
            </aside>

        </div>
    </main>

    <script>
        function toggleForm(id) {
            const form = document.getElementById(id);
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>

</x-foro-layout>
