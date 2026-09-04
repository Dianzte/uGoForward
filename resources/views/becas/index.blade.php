<x-layout>
    <x-slot:titulo>
        {{ __('Becas disponibles') }} - UGF
    </x-slot:titulo>

    <span class="subtitulo">{{ __('Un mar de oportunidades') }}</span>
    <h1 class="importante">{{ __('Explora las becas disponibles') }}</h1>

    <div class="filtros-container">
        <form action="{{ route('becas.filtrar') }}" method="GET" class="filtros">

            <!-- Búsqueda por Texto -->
            <div class="col">
                <label>{{ __('Buscar por palabra clave') }}</label>
                <input type="text" name="buscar" placeholder="{{ __('Ej: Excelencia, Cómputo...') }}"
                    value="{{ request('buscar') }}">
            </div>

            <!-- Filtro por Universidad -->
            <div class="col">
                <label>{{ __('Universidad') }}</label>
                <select name="universidad_id">
                    <option value="">{{ __('Todas las Universidades') }}</option>
                    @foreach ($universidades as $uni)
                        <option value="{{ $uni->id }}"
                            {{ request('universidad_id') == $uni->id ? 'selected' : '' }}>
                            {{ translate_db($uni->nombre_completo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Nivel Académico -->
            <div class="col">
                <label>{{ __('Nivel') }}</label>
                <select name="nivel_academico">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="Técnico" {{ request('nivel_academico') == 'Técnico' ? 'selected' : '' }}>{{ __('Técnico') }}</option>
                    <option value="Licenciatura" {{ request('nivel_academico') == 'Licenciatura' ? 'selected' : '' }}>{{ __('Licenciatura') }}</option>
                    <option value="Ingeniería" {{ request('nivel_academico') == 'Ingeniería' ? 'selected' : '' }}>{{ __('Ingeniería') }}</option>
                    <option value="Maestría" {{ request('nivel_academico') == 'Maestría' ? 'selected' : '' }}>{{ __('Maestría') }}</option>
                </select>
            </div>

            <!-- Filtro por Modalidad -->
            <div class="col">
                <label>{{ __('Modalidad') }}</label>
                <select name="modalidad">
                    <option value="">{{ __('Todas') }}</option>
                    <option value="Presencial" {{ request('modalidad') == 'Presencial' ? 'selected' : '' }}>{{ __('Presencial') }}</option>
                    <option value="Virtual" {{ request('modalidad') == 'Virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
                    <option value="Híbrida" {{ request('modalidad') == 'Híbrida' ? 'selected' : '' }}>{{ __('Híbrida') }}</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="filtros-actions">
                <button type="submit" class="btn-primary" style="padding: 0.65rem 1.4rem;">{{ __('Filtrar') }}</button>
                @if (request()->anyFilled(['buscar', 'universidad_id', 'nivel_academico', 'modalidad']))
                    <a href="{{ route('becas.index') }}" class="btn-filter-clear" title="{{ __('Limpiar Filtros') }}">✕</a>
                @endif
            </div>
        </form>
    </div>

    <div class="indexGrid">
        @forelse ($becas as $beca)
            {{-- ════ TARJETA DE BECA ════ --}}
            <div class="tarjeta" data-beca-id="{{ $beca->id }}">
                {{-- Área clicable para ir al detalle --}}
                <a href="{{ route('becas.show', $beca->id) }}" class="tarjeta-link-overlay" aria-label="{{ translate_db($beca->titulo) }}"></a>

                <div>
                    <div class="tarjeta-header">
                        <span class="tarjeta-badge">{{ translate_db($beca->nivel_academico ?? __('Becas Nacionales')) }}</span>
                        @if($beca->vencimiento)
                            <span style="font-size: 0.75rem; color: var(--gold, #e8c847); font-weight: 600;">
                                 {{ is_string($beca->vencimiento) ? $beca->vencimiento : $beca->vencimiento->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                    <h3 class="tarjeta-titulo">{{ translate_db($beca->titulo) }}</h3>
                    <p class="tarjeta-desc">{{ translate_db($beca->descripcion) }}</p>
                </div>

                <div class="tarjeta-footer">
                    <span class="tarjeta-univ"> {{ translate_db($beca->universidad->nombre_completo ?? 'El Salvador') }}</span>

                    {{-- ════ BOTONES DE ACCIÓN ════ --}}
                    <div class="tarjeta-actions">

                        @auth
                            @if(Auth::user()->role === 'estudiante')
                                {{-- 1. POSTULARSE --}}
                                @php $estaPostulado = in_array($beca->id, $postulacionIds ?? []); @endphp
                                <button
                                    id="btn-postular-{{ $beca->id }}"
                                    class="btn-postular {{ $estaPostulado ? 'postulado' : '' }}"
                                    data-beca-id="{{ $beca->id }}"
                                    data-url="{{ route('becas.postular', $beca->id) }}"
                                    {{ $estaPostulado ? 'disabled' : '' }}
                                    title="{{ $estaPostulado ? __('Ya postulado') : __('Postularse a esta beca') }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); postularBeca(this);">
                                    @if($estaPostulado)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        {{ __('Postulado') }}
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        {{ __('Postular') }}
                                    @endif
                                </button>

                                {{-- 2. GUARDAR / FAVORITO --}}
                                @php $estaGuardado = in_array($beca->id, $guardadoIds ?? []); @endphp
                                <button
                                    id="btn-guardar-{{ $beca->id }}"
                                    class="btn-favorito {{ $estaGuardado ? 'guardado' : '' }}"
                                    data-beca-id="{{ $beca->id }}"
                                    data-url="{{ route('becas.guardar', $beca->id) }}"
                                    title="{{ $estaGuardado ? __('Quitar de favoritos') : __('Guardar beca') }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleGuardar(this);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="{{ $estaGuardado ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                </button>

                                {{-- 3. CHAT CON PADRINO --}}
                                <button
                                    class="btn-chat-padrino"
                                    data-beca-id="{{ $beca->id }}"
                                    data-beca-titulo="{{ translate_db($beca->titulo) }}"
                                    data-url-init="{{ route('becas.chat.init', $beca->id) }}"
                                    data-url-mensaje="{{ route('becas.chat.mensaje', $beca->id) }}"
                                    title="{{ __('Contactar Padrino') }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); abrirChatBeca(this);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                </button>

                            @else
                                {{-- Usuario autenticado pero no es estudiante --}}
                                <a href="{{ route('becas.show', $beca->id) }}" class="tarjeta-btn">{{ __('Ver Detalle →') }}</a>
                            @endif

                        @else
                            {{-- Usuario no autenticado --}}
                            <a href="{{ route('login') }}" class="tarjeta-btn" title="{{ __('Inicia sesión para postularte') }}">{{ __('Ver Beca →') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: var(--text-2);">
                <p style="font-size: 1.1rem; margin-bottom: 12px;">{{ __('No se encontraron becas con los filtros seleccionados.') }}</p>
                <a href="{{ route('becas.index') }}" class="btn-outline">{{ __('Limpiar Filtros') }}</a>
            </div>
        @endforelse
    </div>

    @if (Auth::user() !== null)
    @if (Auth::user()->role == 'padrino')
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 30px;">
        <a href="{{ route('becas.create') }}" class="btn-outline"> {{ __('Sugerir beca') }}</a>
    </div>
    @endif
    @endif

    <div class="mt-4">
        {{ $becas->links('pagination::bootstrap-4') }}
    </div>

    {{-- ════════════════════════════════════════
         MODAL GLOBAL DE CHAT CON PADRINO
    ════════════════════════════════════════ --}}
    @auth
        @if(Auth::user()->role === 'estudiante')
        <div id="modal-chat-beca" class="modal-chat-overlay" style="display:none;" role="dialog" aria-modal="true" aria-label="{{ __('Chat con Padrino') }}">
            <div class="modal-chat-window">
                <!-- Header -->
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

                <!-- Mensajes -->
                <div id="modal-chat-mensajes" class="modal-chat-mensajes">
                    <div class="chat-loading" id="chat-loading">
                        <div class="chat-spinner"></div>
                        <span>{{ __('Cargando chat...') }}</span>
                    </div>
                </div>

                <!-- Input -->
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
// UGF — Becas Interactivas JS
// ═══════════════════════════════════════════════════════

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ─── TOAST ─────────────────────────────────────────────
function showToast(msg, tipo = 'success') {
    const t = document.getElementById('ugf-toast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'ugf-toast show ' + tipo;
    setTimeout(() => { t.className = 'ugf-toast'; }, 3200);
}

// ─── POSTULAR ──────────────────────────────────────────
async function postularBeca(btn) {
    if (btn.disabled) return;
    const becaId = btn.dataset.becaId;
    const url    = btn.dataset.url;

    // Estado de carga
    btn.classList.add('loading');
    btn.disabled = true;

    try {
        const resp = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await resp.json();

        if (!resp.ok) {
            showToast(data.error ?? '{{ __("Error al postularse.") }}', 'error');
            btn.disabled = false;
        } else {
            // Actualizar UI al estado "postulado"
            btn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ __('Postulado') }}`;
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

// ─── GUARDAR / FAVORITO ────────────────────────────────
async function toggleGuardar(btn) {
    const url = btn.dataset.url;

    btn.classList.add('loading');

    try {
        const resp = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await resp.json();

        if (!resp.ok) {
            showToast(data.error ?? '{{ __("Error al guardar.") }}', 'error');
        } else {
            const isGuardado = data.guardado;
            btn.classList.toggle('guardado', isGuardado);

            // Actualizar icono fill
            const svg = btn.querySelector('svg');
            if (svg) svg.setAttribute('fill', isGuardado ? 'currentColor' : 'none');

            showToast(data.mensaje ?? '', isGuardado ? 'success' : 'info');
        }
    } catch (e) {
        showToast('{{ __("Error de conexión.") }}', 'error');
    } finally {
        btn.classList.remove('loading');
    }
}

// ─── CHAT CON PADRINO ──────────────────────────────────
let chatRoomId   = null;
let chatUrlMensaje = null;

async function abrirChatBeca(btn) {
    const modal    = document.getElementById('modal-chat-beca');
    const nombre   = document.getElementById('modal-chat-beca-nombre');
    const mensajes = document.getElementById('modal-chat-mensajes');
    const loading  = document.getElementById('chat-loading');

    if (!modal) return;

    chatUrlMensaje = btn.dataset.urlMensaje;

    // Mostrar modal y estado de carga
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

// Cerrar modal al hacer clic en el overlay
document.getElementById('modal-chat-beca')?.addEventListener('click', function(e) {
    if (e.target === this) cerrarChatBeca();
});

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarChatBeca();
});
</script>
@endpush
