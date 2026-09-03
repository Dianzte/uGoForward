<x-layout>
    <x-slot:titulo>
        {{ __('Información de la beca') }} - {{ translate_db($beca->titulo) }}
    </x-slot:titulo>
    <x-slot:angosto>
        angosto
    </x-slot:angosto>

    <div class="detalle-container">
        <div class="detalle-header">
            <span class="subtitulo">{{ __('Información de la beca') }}</span>
            <h1 class="detalle-title">{{ translate_db($beca->titulo) }}</h1>
            <div class="detalle-univ-badge">
                 {{ translate_db($beca->universidad->nombre_completo ?? 'El Salvador') }}
            </div>
        </div>

        <div class="detalle-section">
            <h3> {{ __('Descripción') }}</h3>
            <p>{{ translate_db($beca->descripcion) }}</p>
        </div>

        <!-- Specifications Grid -->
        <div class="specs-grid">
            @if (isset($beca->nivel_academico))
                <div class="spec-item">
                    <span class="spec-label">{{ __('Nivel académico') }}</span>
                    <span class="spec-value">{{ translate_db($beca->nivel_academico) }}</span>
                </div>
            @endif

            @if (isset($beca->modalidad))
                <div class="spec-item">
                    <span class="spec-label">{{ __('Modalidad') }}</span>
                    <span class="spec-value">{{ translate_db($beca->modalidad) }}</span>
                </div>
            @endif

            @if (isset($beca->vencimiento))
                <div class="spec-item">
                    <span class="spec-label">{{ __('Vencimiento') }}</span>
                    <span class="spec-value" style="color: var(--gold, #e8c847);">
                        {{ is_string($beca->vencimiento) ? $beca->vencimiento : $beca->vencimiento->format('d/m/Y') }}
                    </span>
                </div>
            @endif

            @if (isset($beca->cum_promedio_minimo))
                <div class="spec-item">
                    <span class="spec-label">{{ __('CUM promedio') }}</span>
                    <span class="spec-value">{{ $beca->cum_promedio_minimo }}</span>
                </div>
            @endif
        </div>

        @if (isset($beca->carrera->nombre))
            <div class="detalle-section">
                <h3>{{ __('Carrera') }}</h3>
                <p>{{ translate_db($beca->carrera->nombre) }}</p>
            </div>
        @elseif (!empty($beca->carreras_cobertura))
            <div class="detalle-section">
                <h3> {{ __('Carrera') }}</h3>
                <ul>
                    @foreach (translate_array($beca->carreras_cobertura) as $carrera)
                        <li>{{ $carrera }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($beca->condicion->nombre))
            <div class="detalle-section">
                <h3> {{ __('Condiciones') }}</h3>
                <p>{{ translate_db($beca->condicion->nombre) }}</p>
            </div>
        @endif

        @if (isset($beca->ayuda->nombre))
            <div class="detalle-section">
                <h3> {{ __('Ayuda') }}</h3>
                <p>{{ translate_db($beca->ayuda->nombre) }}</p>
            </div>
        @endif

        @if (!empty($beca->cobertura_resumen))
            <div class="detalle-section">
                <h3> {{ __('Cobertura') }}</h3>
                <p>{{ translate_db($beca->cobertura_resumen) }}</p>
            </div>
        @endif

        @if (!empty($beca->requisitos))
            <div class="detalle-section">
                <h3> {{ __('Requisitos') }}</h3>
                <ul>
                    @foreach (translate_array($beca->requisitos) as $requisito)
                        <li>{{ $requisito }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($beca->imagen->ruta))
            <div class="detalle-section" style="text-align: center;">
                <img src="{{ asset('storage/' . $beca->imagen->ruta) }}" alt="{{ translate_db($beca->titulo) }}"
                    style="max-width: 100%; max-height: 350px; border-radius: 12px; object-fit: cover;">
            </div>
        @endif

        {{-- ════ ACCIONES DEL DETALLE ════ --}}
        <div class="detalle-actions">
            {{-- Visitar sitio oficial --}}
            @if (!empty($beca->url_oficial))
                <a href="{{ $beca->url_oficial }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                     {{ __('Visitar sitio oficial') }} →
                </a>
            @endif

            @auth
                @if(Auth::user()->role === 'estudiante')
                    {{-- 1. POSTULARSE --}}
                    <button
                        id="btn-postular-detalle"
                        class="btn-postular btn-postular-lg {{ $postulado ? 'postulado' : '' }}"
                        data-beca-id="{{ $beca->id }}"
                        data-url="{{ route('becas.postular', $beca->id) }}"
                        {{ $postulado ? 'disabled' : '' }}
                        onclick="postularBeca(this)">
                        @if($postulado)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            {{ __('Ya Postulado') }}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            {{ __('Postularse a esta Beca') }}
                        @endif
                    </button>

                    {{-- 2. GUARDAR / FAVORITO --}}
                    <button
                        id="btn-guardar-detalle"
                        class="btn-favorito btn-favorito-lg {{ $guardado ? 'guardado' : '' }}"
                        data-beca-id="{{ $beca->id }}"
                        data-url="{{ route('becas.guardar', $beca->id) }}"
                        title="{{ $guardado ? __('Quitar de favoritos') : __('Guardar beca') }}"
                        onclick="toggleGuardar(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ $guardado ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        <span id="guardar-label">{{ $guardado ? __('Guardado') : __('Guardar') }}</span>
                    </button>

                    {{-- 3. CHAT CON PADRINO --}}
                    <button
                        class="btn-chat-padrino btn-chat-padrino-lg"
                        data-beca-id="{{ $beca->id }}"
                        data-beca-titulo="{{ addslashes(translate_db($beca->titulo)) }}"
                        data-url-init="{{ route('becas.chat.init', $beca->id) }}"
                        data-url-mensaje="{{ route('becas.chat.mensaje', $beca->id) }}"
                        onclick="abrirChatBeca(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        {{ __('Contactar Padrino') }}
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-outline">
                    🔑 {{ __('Inicia sesión para postularte') }}
                </a>
            @endauth

            <a href="{{ route('becas.index') }}" class="btn-outline">
                ← {{ __('Volver a la lista de becas') }}
            </a>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         MODAL GLOBAL DE CHAT CON PADRINO
    ════════════════════════════════════════ --}}
    @auth
        @if(Auth::user()->role === 'estudiante')
        <div id="modal-chat-beca" class="modal-chat-overlay" style="display:none;" role="dialog" aria-modal="true" aria-label="{{ __('Chat con Padrino') }}">
            <div class="modal-chat-window">
                <div class="modal-chat-header">
                    <div class="modal-chat-title">
                        <div class="modal-chat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div>
                            <span class="modal-chat-label">{{ __('Contactar Padrino') }}</span>
                            <span id="modal-chat-beca-nombre" class="modal-chat-beca-name"></span>
                        </div>
                    </div>
                    <button class="modal-chat-close" onclick="cerrarChatBeca()" title="{{ __('Cerrar') }}">✕</button>
                </div>

                <div id="modal-chat-mensajes" class="modal-chat-mensajes">
                    <div class="chat-loading" id="chat-loading">
                        <div class="chat-spinner"></div>
                        <span>{{ __('Cargando chat...') }}</span>
                    </div>
                </div>

                <div class="modal-chat-input-area">
                    <input
                        type="text"
                        id="chat-input-mensaje"
                        class="modal-chat-input"
                        placeholder="{{ __('Escribe tu mensaje...') }}"
                        maxlength="1000"
                        onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); enviarMensajeChat(); }"
                    />
                    <button class="modal-chat-send" onclick="enviarMensajeChat()" title="{{ __('Enviar') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @endif
    @endauth

    {{-- Toast de notificación --}}
    <div id="ugf-toast" class="ugf-toast" role="alert" aria-live="polite"></div>

</x-layout>

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════
// UGF — Becas Interactivas JS (Detalle)
// ═══════════════════════════════════════════════════════

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function showToast(msg, tipo = 'success') {
    const t = document.getElementById('ugf-toast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'ugf-toast show ' + tipo;
    setTimeout(() => { t.className = 'ugf-toast'; }, 3200);
}

async function postularBeca(btn) {
    if (btn.disabled) return;
    btn.classList.add('loading');
    btn.disabled = true;

    try {
        const resp = await fetch(btn.dataset.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await resp.json();

        if (!resp.ok) {
            showToast(data.error ?? '{{ __("Error al postularse.") }}', 'error');
            btn.disabled = false;
        } else {
            btn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ __("Ya Postulado") }}`;
            btn.classList.remove('loading');
            btn.classList.add('postulado');
            showToast(data.mensaje ?? '{{ __("¡Postulación enviada!") }}', 'success');
        }
    } catch (e) {
        showToast('{{ __("Error de conexión.") }}', 'error');
        btn.disabled = false;
    } finally {
        btn.classList.remove('loading');
    }
}

async function toggleGuardar(btn) {
    btn.classList.add('loading');
    try {
        const resp = await fetch(btn.dataset.url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await resp.json();
        if (!resp.ok) {
            showToast(data.error ?? '{{ __("Error al guardar.") }}', 'error');
        } else {
            const isGuardado = data.guardado;
            btn.classList.toggle('guardado', isGuardado);
            const svg = btn.querySelector('svg');
            if (svg) svg.setAttribute('fill', isGuardado ? 'currentColor' : 'none');
            const label = document.getElementById('guardar-label');
            if (label) label.textContent = isGuardado ? '{{ __("Guardado") }}' : '{{ __("Guardar") }}';
            showToast(data.mensaje ?? '', isGuardado ? 'success' : 'info');
        }
    } catch (e) {
        showToast('{{ __("Error de conexión.") }}', 'error');
    } finally {
        btn.classList.remove('loading');
    }
}

let chatRoomId = null;
let chatUrlMensaje = null;

async function abrirChatBeca(btn) {
    const modal    = document.getElementById('modal-chat-beca');
    const nombre   = document.getElementById('modal-chat-beca-nombre');
    const mensajes = document.getElementById('modal-chat-mensajes');
    const loading  = document.getElementById('chat-loading');
    if (!modal) return;

    chatUrlMensaje = btn.dataset.urlMensaje;
    if (nombre) nombre.textContent = btn.dataset.becaTitulo;
    mensajes.innerHTML = '';
    if (loading) { loading.style.display = 'flex'; mensajes.appendChild(loading); }
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    try {
        const resp = await fetch(btn.dataset.urlInit, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await resp.json();
        chatRoomId = data.room_id;
        loading?.remove();
        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(m => agregarMensajeChat(m, false));
        } else {
            mensajes.innerHTML = `<div class="chat-empty">{{ __("Inicia la conversación con tu padrino.") }}</div>`;
        }
        scrollChatAbajo();
    } catch (e) {
        mensajes.innerHTML = `<div class="chat-empty">{{ __("Error al cargar el chat.") }}</div>`;
    }
}

function cerrarChatBeca() {
    const modal = document.getElementById('modal-chat-beca');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
    chatRoomId = null;
}

async function enviarMensajeChat() {
    if (!chatRoomId || !chatUrlMensaje) return;
    const input = document.getElementById('chat-input-mensaje');
    const texto = input?.value.trim();
    if (!texto) return;
    input.value = '';
    input.disabled = true;
    try {
        const resp = await fetch(chatUrlMensaje, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ contenido: texto, room_id: chatRoomId })
        });
        const data = await resp.json();
        if (data.success) {
            agregarMensajeChat(data.message, true);
            scrollChatAbajo();
        } else {
            showToast(data.error ?? '{{ __("Error al enviar.") }}', 'error');
        }
    } catch (e) {
        showToast('{{ __("Error de conexión.") }}', 'error');
    } finally {
        input.disabled = false;
        input.focus();
    }
}

function agregarMensajeChat(msg, animate = true) {
    const mensajes = document.getElementById('modal-chat-mensajes');
    const div = document.createElement('div');
    div.className = 'chat-msg ' + (msg.mio ? 'chat-msg-mio' : 'chat-msg-otro') + (animate ? ' chat-msg-new' : '');
    div.innerHTML = `
        ${!msg.mio ? `<span class="chat-msg-autor">${escapeHtml(msg.autor)}</span>` : ''}
        <div class="chat-bubble">${escapeHtml(msg.contenido)}</div>
        <span class="chat-msg-hora">${escapeHtml(msg.created_at)}</span>
    `;
    mensajes.appendChild(div);
}

function scrollChatAbajo() {
    const mensajes = document.getElementById('modal-chat-mensajes');
    if (mensajes) mensajes.scrollTop = mensajes.scrollHeight;
}

function escapeHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

document.getElementById('modal-chat-beca')?.addEventListener('click', function(e) {
    if (e.target === this) cerrarChatBeca();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarChatBeca();
});
</script>
@endpush
