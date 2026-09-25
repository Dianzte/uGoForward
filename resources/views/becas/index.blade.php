<x-layout>
    <x-slot:titulo>
        {{ __('Becas disponibles') }} - UGF
    </x-slot:titulo>

    <span class="subtitulo">{{ __('Un mar de oportunidades') }}</span>
    <h1 class="importante">{{ __('Explora las becas disponibles') }}</h1>

    <div class="filtros-container" style="display:none;">
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
                                    data-beca-titulo="{{ addslashes(translate_db($beca->titulo)) }}"
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

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 30px;">
        <a href="{{ route('becas.create') }}" class="btn-outline"> {{ __('Sugerir beca') }}</a>
    </div>

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
// UGF — Becas Interactivas JS (Index)
// ═══════════════════════════════════════════════════════

// ── CSRF Token ──────────────────────────────────────────────────
const _ugfCsrf = document.querySelector('meta[name="csrf-token"]');
if (!_ugfCsrf) {
    console.error('[UGF Becas] CSRF meta tag no encontrado. Los botones no funcionarán.');
}
const UGF_CSRF = _ugfCsrf ? _ugfCsrf.content : '';

// ── TOAST ────────────────────────────────────────────────────────
window.showToast = function(msg, tipo) {
    tipo = tipo || 'success';
    const t = document.getElementById('ugf-toast');
    if (!t) { console.warn('[UGF] Toast element not found'); return; }
    t.textContent = msg;
    t.className = 'ugf-toast show ' + tipo;
    clearTimeout(t._ugfTimer);
    t._ugfTimer = setTimeout(function() { t.className = 'ugf-toast'; }, 3200);
};

// ── POSTULAR ──────────────────────────────────────────────────────
window.postularBeca = async function(btn) {
    console.log('[UGF] postularBeca llamado', btn);

    if (!btn) { console.error('[UGF] btn es null en postularBeca'); return; }
    if (btn.disabled || btn.classList.contains('postulado')) {
        console.log('[UGF] Botón ya deshabilitado/postulado');
        return;
    }

    const url = btn.dataset.url;
    if (!url) { console.error('[UGF] data-url no encontrado en el botón'); return; }
    if (!UGF_CSRF) { window.showToast('Error de sesión. Recarga la página.', 'error'); return; }

    const textoOriginal = btn.innerHTML;
    btn.classList.add('loading');
    btn.disabled = true;

    try {
        console.log('[UGF] Enviando POST a:', url);
        const resp = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': UGF_CSRF,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        console.log('[UGF] Respuesta status:', resp.status);
        const data = await resp.json();
        console.log('[UGF] Respuesta data:', data);

        if (!resp.ok) {
            window.showToast(data.error || '{{ __("Error al postularse.") }}', 'error');
            btn.disabled = false;
            btn.innerHTML = textoOriginal;
        } else {
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> {{ __("Postulado") }}';
            btn.classList.remove('loading');
            btn.classList.add('postulado');
            window.showToast(data.mensaje || '{{ __("¡Postulación enviada!") }}', 'success');
        }
    } catch (e) {
        console.error('[UGF] Error en postularBeca:', e);
        window.showToast('{{ __("Error de conexión.") }}', 'error');
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
    } finally {
        btn.classList.remove('loading');
    }
};

// ── GUARDAR / FAVORITO ────────────────────────────────────────────
window.toggleGuardar = async function(btn) {
    console.log('[UGF] toggleGuardar llamado', btn);

    if (!btn) { console.error('[UGF] btn es null en toggleGuardar'); return; }

    const url = btn.dataset.url;
    if (!url) { console.error('[UGF] data-url no encontrado en btn guardar'); return; }
    if (!UGF_CSRF) { window.showToast('Error de sesión. Recarga la página.', 'error'); return; }

    btn.classList.add('loading');

    try {
        console.log('[UGF] Enviando POST guardar a:', url);
        const resp = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': UGF_CSRF,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        console.log('[UGF] Guardar status:', resp.status);
        const data = await resp.json();
        console.log('[UGF] Guardar data:', data);

        if (!resp.ok) {
            window.showToast(data.error || '{{ __("Error al guardar.") }}', 'error');
        } else {
            const isGuardado = data.guardado;
            btn.classList.toggle('guardado', isGuardado);
            const svg = btn.querySelector('svg');
            if (svg) svg.setAttribute('fill', isGuardado ? 'currentColor' : 'none');
            window.showToast(data.mensaje || '', isGuardado ? 'success' : 'info');
        }
    } catch (e) {
        console.error('[UGF] Error en toggleGuardar:', e);
        window.showToast('{{ __("Error de conexión.") }}', 'error');
    } finally {
        btn.classList.remove('loading');
    }
};

// ── CHAT CON PADRINO ──────────────────────────────────────────────
let _ugfChatRoomId   = null;
let _ugfChatUrlMsg   = null;

window.abrirChatBeca = async function(btn) {
    console.log('[UGF] abrirChatBeca llamado', btn);

    if (!btn) { console.error('[UGF] btn es null en abrirChatBeca'); return; }

    const modal    = document.getElementById('modal-chat-beca');
    const nombre   = document.getElementById('modal-chat-beca-nombre');
    const mensajes = document.getElementById('modal-chat-mensajes');

    if (!modal) { console.error('[UGF] #modal-chat-beca no encontrado en el DOM'); return; }

    _ugfChatUrlMsg = btn.dataset.urlMensaje;
    console.log('[UGF] URL init:', btn.dataset.urlInit, 'URL msg:', _ugfChatUrlMsg);

    if (nombre) nombre.textContent = btn.dataset.becaTitulo || '';
    mensajes.innerHTML = '<div class="chat-loading"><div class="chat-spinner"></div><span>{{ __("Cargando chat...") }}</span></div>';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    try {
        const resp = await fetch(btn.dataset.urlInit, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': UGF_CSRF,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        console.log('[UGF] Chat init status:', resp.status);
        const data = await resp.json();
        console.log('[UGF] Chat init data:', data);

        _ugfChatRoomId = data.room_id;
        mensajes.innerHTML = '';

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(function(m) { _ugfAgregarMensaje(m, false); });
        } else {
            mensajes.innerHTML = '<div class="chat-empty">{{ __("Inicia la conversación con tu padrino.") }}</div>';
        }
        _ugfScrollAbajo();
    } catch (e) {
        console.error('[UGF] Error en abrirChatBeca:', e);
        mensajes.innerHTML = '<div class="chat-empty">{{ __("Error al cargar el chat.") }}</div>';
    }
};

window.cerrarChatBeca = function() {
    const modal = document.getElementById('modal-chat-beca');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
    _ugfChatRoomId = null;
};

window.enviarMensajeChat = async function() {
    if (!_ugfChatRoomId || !_ugfChatUrlMsg) {
        console.warn('[UGF] enviarMensajeChat: chatRoomId o urlMensaje no definidos');
        return;
    }
    const input = document.getElementById('chat-input-mensaje');
    const texto = input ? input.value.trim() : '';
    if (!texto) return;

    input.value = '';
    input.disabled = true;

    try {
        const resp = await fetch(_ugfChatUrlMsg, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': UGF_CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ contenido: texto, room_id: _ugfChatRoomId })
        });
        const data = await resp.json();
        if (data.success) {
            _ugfAgregarMensaje(data.message, true);
            _ugfScrollAbajo();
        } else {
            window.showToast(data.error || '{{ __("Error al enviar.") }}', 'error');
        }
    } catch (e) {
        console.error('[UGF] Error en enviarMensajeChat:', e);
        window.showToast('{{ __("Error de conexión.") }}', 'error');
    } finally {
        if (input) { input.disabled = false; input.focus(); }
    }
};

function _ugfAgregarMensaje(msg, animate) {
    const mensajes = document.getElementById('modal-chat-mensajes');
    if (!mensajes) return;
    const div = document.createElement('div');
    div.className = 'chat-msg ' + (msg.mio ? 'chat-msg-mio' : 'chat-msg-otro') + (animate ? ' chat-msg-new' : '');
    div.innerHTML =
        (!msg.mio ? '<span class="chat-msg-autor">' + _ugfEscape(msg.autor) + '</span>' : '') +
        '<div class="chat-bubble">' + _ugfEscape(msg.contenido) + '</div>' +
        '<span class="chat-msg-hora">' + _ugfEscape(msg.created_at) + '</span>';
    mensajes.appendChild(div);
}

function _ugfScrollAbajo() {
    const mensajes = document.getElementById('modal-chat-mensajes');
    if (mensajes) mensajes.scrollTop = mensajes.scrollHeight;
}

function _ugfEscape(str) {
    return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ── Cerrar modal con Escape o click en overlay ───────────────────
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modal-chat-beca');
    if (modal && e.target === modal) window.cerrarChatBeca();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') window.cerrarChatBeca();
});

console.log('[UGF Becas] JS cargado. Funciones:', {
    postularBeca: typeof window.postularBeca,
    toggleGuardar: typeof window.toggleGuardar,
    abrirChatBeca: typeof window.abrirChatBeca,
    CSRF: UGF_CSRF ? 'OK (' + UGF_CSRF.substring(0, 8) + '...)' : 'FALTANTE'
});
</script>
@endpush
