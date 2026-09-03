<x-foro-layout>
    @push('estilo')
        @vite(['resources/css/foro/index.css'])
    @endpush

    <header class="foro-header">
        <div class="container header-titles">
            <span class="subtitle">{{ __('Explora, comparte, aprende') }}</span>
            <h1 class="main-title">{{ __('Foro estudiantil') }}</h1>
        </div>
    </header>

    <main class="foro-main">
        <div class="container main-container">

            <section class="active-thread-container">
                <div class="thread-card">
                    @if (isset($ejemplo))
                        <div class="thread-header">
                            <span class="thread-meta">
                                {{ __('Publicado por') }} • {{ $ejemplo->usuario->usuario ?? auth()->user()->usuario ?? 'Estudiante' }}
                                {{ $ejemplo->created_at ? $ejemplo->created_at->diffForHumans() : '' }}
                            </span>
                            <h2 class="thread-title">{{ translate_db($ejemplo->titulo) }}</h2>
                        </div>
                        <div class="thread-body">
                            <p>{{ translate_db($ejemplo->contenido) }}</p>
                        </div>
                    @else
                        <div class="thread-header">
                            <span class="thread-meta">{{ __('Publicado por') }} @equipoUGF</span>
                            <h2 class="thread-title">{{ __('Bienvenido') }}</h2>
                        </div>
                        <div class="thread-body">
                            <p>{{ __('Comparte tus preguntas y tus conocimientos.') }}</p>
                        </div>
                    @endif

                    @if (isset($ejemplo))
                        <div class="thread-footer">
                            <div class="interaction-buttons">
                                <span class="btn-action">
                                    <span class="icon">👁️</span> {{ $ejemplo->visitas_count ?? 1 }}
                                </span>
                            </div>
                        </div>

                        <div class="comments-section">
                            <div class="comments-header">
                                <h3 class="comments-title">
                                    <span>💬</span> {{ __('Comentarios') }}
                                    <span class="comments-count-badge">{{ $ejemplo->comentarios->count() }}</span>
                                </h3>
                            </div>

                            @auth
                                <form class="comment-form" action="{{ route('comentario.store', $ejemplo) }}" method="POST">
                                    @csrf
                                    <div class="comment-form-wrapper">
                                        <div class="comment-avatar user-avatar" style="{{ auth()->user()->avatarImg ? 'background-image: url(' . asset('storage/' . auth()->user()->avatarImg->ruta) . ')' : '' }}">
                                         
                                        </div>
                                        <div class="comment-input-group">
                                            <textarea name="contenido" class="comment-textarea" placeholder="{{ __('Escribe un comentario o respuesta...') }}" rows="3" required></textarea>
                                            <div class="comment-form-actions">
                                                <button type="submit" class="btn-submit-comment">
                                                    <span>{{ __('Publicar comentario') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(100,200,255,0.1); border-radius: 14px; padding: 16px; margin-bottom: 24px; text-align: center;">
                                    <p style="margin-bottom: 8px;">{{ __('Inicia sesión para participar en la conversación.') }}</p>
                                    <a href="{{ route('login') }}" class="btn-primary" style="padding: 0.4rem 1.2rem; font-size: 0.82rem;">{{ __('Iniciar sesión') }}</a>
                                </div>
                            @endauth

                            <div class="comments-list">
                                @forelse ($ejemplo->comentarios as $comentario)
                                    @if ($comentario->padre_id == null)
                                        <div class="comment-item">
                                            <div class="comment-avatar" style="{{ $comentario->comentarista?->avatarImg ? 'background-image: url(' . asset('storage/' . $comentario->comentarista?->avatarImg->ruta) . ')' : '' }}">
                                              
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-meta">
                                                    <strong class="comment-author">{{ $comentario->comentarista?->nombre ?? $comentario->comentarista?->usuario ?? 'Anónimo' }}</strong>
                                                    <span class="comment-date">{{ $comentario->created_at ? $comentario->created_at->diffForHumans() : '' }}</span>
                                                </div>
                                                <p class="comment-text">{{ translate_db($comentario->contenido) }}</p>
                                                
                                                @auth
                                                    <div class="comment-actions">
                                                        <button type="button" class="btn-comment-action" onclick="toggleForm('form-respuesta-{{ $comentario->id }}')">
                                                            <span class="icon">↩</span> {{ __('Responder') }}
                                                        </button>
                                                    </div>

                                                    <form id="form-respuesta-{{ $comentario->id }}" action="{{ route('comentario.store', $ejemplo) }}" method="POST" style="display: none; margin-top: 12px; background: rgba(0,0,0,0.25); padding: 12px; border-radius: 12px; border: 1px solid rgba(100,200,255,0.1);">
                                                        @csrf
                                                        <input type="hidden" name="padre_id" value="{{ $comentario->id }}">
                                                        <textarea name="contenido" rows="2" placeholder="{{ __('Escribe un comentario o respuesta...') }}" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(100,200,255,0.15); border-radius: 8px; color: #fff; padding: 8px; box-sizing: border-box; margin-bottom: 8px; outline: none;"></textarea>
                                                        <button type="submit" class="btn-primary" style="padding: 4px 14px; font-size: 0.8rem;">{{ __('Publicar respuesta') }}</button>
                                                    </form>
                                                @endauth

                                                @if ($comentario->respuestas && $comentario->respuestas->count() > 0)
                                                    <div class="respuestas-anidadas">
                                                        @foreach ($comentario->respuestas as $respuesta)
                                                            <div class="respuesta-item">
                                                                <strong>{{ $respuesta->comentarista?->nombre ?? $respuesta->comentarista?->usuario ?? 'Anónimo' }}</strong>
                                                                <small>{{ $respuesta->created_at ? $respuesta->created_at->diffForHumans() : '' }}</small>
                                                                <p>{{ translate_db($respuesta->contenido) }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <p style="color: var(--text-2); text-align: center; padding: 20px 0;">{{ __('Aún no hay respuestas en esta publicación') }}</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <aside class="threads-sidebar">
                <h2 class="sidebar-title">{{ __('Todos los foros') }}</h2>

                <div class="threads-list">
                    @forelse ($foros as $foro)
                        <a href="{{ route('foro.show', $foro->slug) }}" class="thread-link-card {{ isset($ejemplo) && $ejemplo->id === $foro->id ? 'active' : '' }}">
                            <span class="thread-card-title">{{ translate_db($foro->titulo) }}</span>
                        </a>
                    @empty
                    @endforelse

                    <a href="{{ route('foro.crear') }}" class="btn-primary" style="margin-top: 10px; justify-content: center;">
                         {{ __('Crear un foro') }}
                    </a>
                </div>
            </aside>

        </div>
    </main>

    <script>
        function toggleForm(id) {
            const form = document.getElementById(id);
            if (!form) return;
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }
    </script>
</x-foro-layout>
