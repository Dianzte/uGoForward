<x-hub-layout title="Muro Académico">

<div class="hub-feed-layout">

    {{-- ══ FEED PRINCIPAL ══ --}}
    <div class="hub-feed-main">

        {{-- Formulario para crear aporte --}}
        <div class="hub-card" style="margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div class="hub-avatar hub-avatar-md">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="">
                    @else
                        {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                    @endif
                </div>
                <button id="openPostForm"
                        style="flex:1;background:var(--hub-surface-2);border:1px solid var(--hub-border);border-radius:10px;padding:10px 16px;color:var(--hub-text-muted);font-family:inherit;font-size:13.5px;text-align:left;cursor:pointer;transition:all 0.2s;"
                        onmouseover="this.style.borderColor='var(--hub-violet-light)'"
                        onmouseout="this.style.borderColor='var(--hub-border)'">
                    ✍️ Compartir un aporte académico...
                </button>
            </div>

            {{-- Formulario expandible --}}
            <form id="postForm" method="POST" action="{{ route('hub.posts.store') }}"
                  style="display:none;" onsubmit="submitPost(event)">
                @csrf
                <div class="hub-form-group">
                    <label class="hub-label">Título del aporte</label>
                    <input type="text" name="titulo" class="hub-input"
                           placeholder="Ej: Resumen de Cálculo Diferencial - Derivadas" required maxlength="200">
                </div>

                <div class="hub-form-group">
                    <label class="hub-label">Contenido</label>
                    <textarea name="contenido" class="hub-input hub-textarea" rows="4"
                              placeholder="Comparte tu resumen, guía, tip o enlace aquí..." required maxlength="5000"></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                    <div class="hub-form-group">
                        <label class="hub-label">Tipo</label>
                        <select name="tipo" class="hub-select" required>
                            <option value="resumen">📝 Resumen</option>
                            <option value="enlace">🔗 Enlace</option>
                            <option value="guia">📖 Guía</option>
                            <option value="tip">💡 Tip</option>
                        </select>
                    </div>
                    <div class="hub-form-group">
                        <label class="hub-label">Materia (opcional)</label>
                        <input type="text" name="materia" class="hub-input"
                               placeholder="Ej: Cálculo I" maxlength="100">
                    </div>
                    <div class="hub-form-group">
                        <label class="hub-label">Etiquetas (separadas por coma)</label>
                        <input type="text" name="etiquetas" class="hub-input"
                               placeholder="#calculo, #derivadas" maxlength="200">
                    </div>
                </div>

                <div class="hub-form-group">
                    <label class="hub-label">URL adjunta (opcional)</label>
                    <input type="url" name="url_adjunto" class="hub-input"
                           placeholder="https://..." maxlength="500">
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" class="hub-btn hub-btn-secondary hub-btn-sm"
                            onclick="document.getElementById('postForm').style.display='none'">
                        Cancelar
                    </button>
                    <button type="submit" class="hub-btn hub-btn-primary hub-btn-sm" id="postSubmitBtn">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Publicar aporte
                    </button>
                </div>
            </form>
        </div>

        {{-- Filtros --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
            <a href="{{ route('hub.feed') }}"
               class="hub-btn hub-btn-sm {{ !request('materia') && !request('tipo') ? 'hub-btn-primary' : 'hub-btn-secondary' }}">
                Todos
            </a>
            @foreach(['resumen'=>'📝 Resúmenes','enlace'=>'🔗 Enlaces','guia'=>'📖 Guías','tip'=>'💡 Tips'] as $val => $label)
                <a href="{{ route('hub.feed', ['tipo' => $val]) }}"
                   class="hub-btn hub-btn-sm {{ request('tipo') === $val ? 'hub-btn-primary' : 'hub-btn-secondary' }}">
                    {{ $label }}
                </a>
            @endforeach

            @if($materias->count())
                <div style="display:flex;gap:6px;flex-wrap:wrap;padding-left:8px;border-left:1px solid var(--hub-border);">
                    @foreach($materias->take(6) as $mat)
                        <a href="{{ route('hub.feed', ['materia' => $mat]) }}"
                           class="hub-btn hub-btn-sm {{ request('materia') === $mat ? 'hub-btn-primary' : 'hub-btn-ghost' }}">
                            {{ $mat }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Lista de Posts --}}
        <div id="postsList">
            @forelse($posts as $post)
                <div class="hub-card" style="margin-bottom:14px;" id="post-{{ $post->id }}">
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        {{-- Avatar --}}
                        <div class="hub-avatar hub-avatar-md" style="flex-shrink:0;">
                            @if($post->user->avatar)
                                <img src="{{ $post->user->avatar }}" alt="">
                            @else
                                {{ strtoupper(substr($post->user->nombre, 0, 1)) }}
                            @endif
                        </div>

                        <div style="flex:1;min-width:0;">
                            {{-- Meta --}}
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                                <span style="font-weight:600;font-size:13.5px;color:var(--hub-text);">
                                    {{ $post->user->nombre }}
                                </span>
                                <span style="font-size:11px;color:var(--hub-text-muted);">
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                                @php
                                    $tipoMap = ['resumen'=>['📝','violet'],'enlace'=>['🔗','cyan'],'guia'=>['📖','green'],'tip'=>['💡','orange']];
                                    [$icon,$color] = $tipoMap[$post->tipo] ?? ['📄','violet'];
                                @endphp
                                <span class="hub-badge hub-badge-{{ $color }}">{{ $icon }} {{ ucfirst($post->tipo) }}</span>
                                @if($post->materia)
                                    <span class="hub-badge hub-badge-violet">{{ $post->materia }}</span>
                                @endif
                            </div>

                            {{-- Título y Contenido --}}
                            <h3 style="font-size:15px;font-weight:700;color:var(--hub-text);margin-bottom:8px;line-height:1.4;">
                                {{ $post->titulo }}
                            </h3>
                            <p style="font-size:13.5px;color:var(--hub-text-sub);line-height:1.6;margin-bottom:10px;">
                                {{ Str::limit($post->contenido, 280) }}
                            </p>

                            {{-- URL adjunta --}}
                            @if($post->url_adjunto)
                                <a href="{{ $post->url_adjunto }}" target="_blank" rel="noopener"
                                   style="display:inline-flex;align-items:center;gap:6px;color:var(--hub-cyan);font-size:12px;text-decoration:none;margin-bottom:10px;word-break:break-all;">
                                    🔗 {{ $post->url_adjunto }}
                                </a>
                            @endif

                            {{-- Etiquetas --}}
                            @if($post->etiquetas && count($post->etiquetas))
                                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
                                    @foreach($post->etiquetas as $tag)
                                        <a href="{{ route('hub.feed', ['q' => $tag]) }}"
                                           style="color:var(--hub-violet-light);font-size:12px;text-decoration:none;">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Acciones --}}
                            <div style="display:flex;align-items:center;gap:10px;">
                                <button class="hub-upvote-btn {{ Auth::user() && $post->yaVotadoPor(Auth::id()) ? 'active' : '' }}"
                                        onclick="toggleUpvote({{ $post->id }}, this)"
                                        id="upvote-{{ $post->id }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ Auth::user() && $post->yaVotadoPor(Auth::id()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                    </svg>
                                    <span id="upvote-count-{{ $post->id }}">{{ $post->upvotes_count }}</span>
                                    Me sirvió
                                </button>

                                <button class="hub-btn hub-btn-ghost hub-btn-sm"
                                        onclick="toggleComentarios({{ $post->id }})">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    {{ $post->comentarios_count }} comentarios
                                </button>
                            </div>

                            {{-- Sección de comentarios (oculta por defecto) --}}
                            <div id="comentarios-{{ $post->id }}" style="display:none;margin-top:14px;">
                                <hr class="hub-divider">

                                {{-- Comentarios existentes --}}
                                @foreach($post->comentarios->take(5) as $com)
                                    <div style="display:flex;gap:8px;margin-bottom:10px;">
                                        <div class="hub-avatar hub-avatar-sm">
                                            {{ strtoupper(substr($com->user->nombre ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span style="font-weight:600;font-size:12px;color:var(--hub-violet-light);">
                                                {{ $com->user->nombre ?? 'Usuario' }}
                                            </span>
                                            <span style="font-size:11px;color:var(--hub-text-muted);margin-left:6px;">
                                                {{ $com->created_at->diffForHumans() }}
                                            </span>
                                            <p style="font-size:13px;color:var(--hub-text-sub);margin-top:3px;">
                                                {{ $com->contenido }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Formulario de nuevo comentario --}}
                                <div style="display:flex;gap:8px;margin-top:8px;">
                                    <div class="hub-avatar hub-avatar-sm">
                                        {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                                    </div>
                                    <div style="flex:1;display:flex;gap:8px;">
                                        <input type="text"
                                               class="hub-input"
                                               id="com-input-{{ $post->id }}"
                                               placeholder="Escribe un comentario..."
                                               style="flex:1;padding:7px 12px;"
                                               onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();enviarComentario({{ $post->id }});}">
                                        <button class="hub-btn hub-btn-primary hub-btn-sm"
                                                onclick="enviarComentario({{ $post->id }})">
                                            Enviar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="hub-empty">
                    <div class="hub-empty-icon">📭</div>
                    <div class="hub-empty-title">Sin aportes aún</div>
                    <div class="hub-empty-desc">¡Sé el primero en compartir un recurso académico!</div>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($posts->hasPages())
            <div style="margin-top:20px;display:flex;justify-content:center;">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    {{-- ══ ASIDE / SIDEBAR DEL FEED ══ --}}
    <aside class="hub-feed-aside">

        {{-- Ir al Chat --}}
        <div class="hub-card" style="margin-bottom:16px;text-align:center;background:linear-gradient(135deg,rgba(124,58,237,0.15),rgba(79,70,229,0.1));border-color:rgba(124,58,237,0.3);">
            <div style="font-size:32px;margin-bottom:8px;">💬</div>
            <h3 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:15px;margin-bottom:6px;">Chat en Vivo</h3>
            <p style="font-size:12.5px;color:var(--hub-text-sub);margin-bottom:14px;">Conecta con compañeros en tiempo real</p>
            <a href="{{ route('hub.chat') }}" class="hub-btn hub-btn-primary" style="width:100%;justify-content:center;">
                Entrar al Chat
            </a>
        </div>

        {{-- Mis metas activas --}}
        <div class="hub-card">
            <h3 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                🎯 <span>Mis Metas Activas</span>
            </h3>
            <p style="font-size:12.5px;color:var(--hub-text-muted);margin-bottom:12px;">Mantén el enfoque en tus objetivos académicos.</p>
            <a href="{{ route('hub.goals') }}" class="hub-btn hub-btn-secondary hub-btn-sm" style="width:100%;justify-content:center;">
                Ver mis metas
            </a>
        </div>
    </aside>
</div>

{{-- ══ SCRIPTS ══ --}}
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

// Mostrar formulario de post
document.getElementById('openPostForm')?.addEventListener('click', () => {
    document.getElementById('postForm').style.display = 'block';
});

// Enviar post vía AJAX
function submitPost(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('postSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Publicando...';

    const formData = new FormData(form);

    fetch('{{ route("hub.posts.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: formData,
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            form.reset();
            form.style.display = 'none';
            // Insertar la nueva tarjeta al tope del feed
            insertPostCard(data.post);
        }
    })
    .catch(() => alert('Error al publicar. Intenta de nuevo.'))
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Publicar aporte';
    });
}

function insertPostCard(post) {
    const tipos = { resumen: ['📝','violet'], enlace: ['🔗','cyan'], guia: ['📖','green'], tip: ['💡','orange'] };
    const [icon, color] = tipos[post.tipo] || ['📄','violet'];
    const card = document.createElement('div');
    card.className = 'hub-card is-new';
    card.style.marginBottom = '14px';
    card.id = `post-${post.id}`;
    card.innerHTML = `
        <div style="display:flex;align-items:flex-start;gap:12px;">
            <div class="hub-avatar hub-avatar-md" style="flex-shrink:0;">${post.user.nombre[0].toUpperCase()}</div>
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span style="font-weight:600;font-size:13.5px;color:var(--hub-text);">${post.user.nombre}</span>
                    <span style="font-size:11px;color:var(--hub-text-muted);">Ahora</span>
                    <span class="hub-badge hub-badge-${color}">${icon} ${post.tipo.charAt(0).toUpperCase()+post.tipo.slice(1)}</span>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:var(--hub-text);margin-bottom:8px;">${post.titulo}</h3>
                <p style="font-size:13.5px;color:var(--hub-text-sub);line-height:1.6;">${post.contenido.substring(0,280)}</p>
                <div style="margin-top:12px;">
                    <button class="hub-upvote-btn" onclick="toggleUpvote(${post.id},this)" id="upvote-${post.id}">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        <span id="upvote-count-${post.id}">0</span> Me sirvió
                    </button>
                </div>
            </div>
        </div>`;
    const list = document.getElementById('postsList');
    list.insertBefore(card, list.firstChild);
}

// Toggle upvote
function toggleUpvote(postId, btn) {
    fetch(`/hub/posts/${postId}/upvote`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById(`upvote-count-${postId}`).textContent = data.upvotes_count;
        btn.classList.toggle('active', data.votado);
        const svg = btn.querySelector('svg');
        svg.setAttribute('fill', data.votado ? 'currentColor' : 'none');
    });
}

// Toggle sección de comentarios
function toggleComentarios(postId) {
    const el = document.getElementById(`comentarios-${postId}`);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

// Enviar comentario
function enviarComentario(postId) {
    const input = document.getElementById(`com-input-${postId}`);
    const texto = input.value.trim();
    if (!texto) return;

    fetch(`/hub/posts/${postId}/comentar`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ contenido: texto }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            const com = data.comentario;
            const container = document.querySelector(`#comentarios-${postId} .hub-divider`);
            const div = document.createElement('div');
            div.style.cssText = 'display:flex;gap:8px;margin-bottom:10px;';
            div.innerHTML = `
                <div class="hub-avatar hub-avatar-sm">${com.user.nombre[0].toUpperCase()}</div>
                <div>
                    <span style="font-weight:600;font-size:12px;color:var(--hub-violet-light);">${com.user.nombre}</span>
                    <span style="font-size:11px;color:var(--hub-text-muted);margin-left:6px;">Ahora</span>
                    <p style="font-size:13px;color:var(--hub-text-sub);margin-top:3px;">${com.contenido}</p>
                </div>`;
            container.insertAdjacentElement('afterend', div);
        }
    });
}
</script>
</x-hub-layout>
