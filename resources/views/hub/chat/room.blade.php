<x-hub-layout title="Chat Estudiantil">
{{-- Chat usa su propio layout de pantalla completa --}}
<style>
    /* Sobrescribir el padding del hub-content para el chat */
    .hub-content { padding: 0 !important; height: calc(100vh - 64px); overflow: hidden; display: flex; flex-direction: column; }
</style>

<div class="hub-chat-layout">

    {{-- ══ SIDEBAR DE SALAS ══ --}}
    <div class="hub-chat-sidebar">
        <div style="padding:8px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--hub-text-muted);">
            Salas
        </div>

        @foreach($rooms as $room)
            <a href="{{ route('hub.chat.room', $room->slug) }}"
               class="hub-chat-room-item {{ isset($currentRoom) && $currentRoom->id === $room->id ? 'active' : '' }}">
                <span class="hub-chat-room-icon">{{ $room->icono ?? '💬' }}</span>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $room->nombre }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- ══ ÁREA DE MENSAJES ══ --}}
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden;">
        {{-- Header de la sala --}}
        <div style="padding:16px 20px;border-bottom:1px solid var(--hub-border);display:flex;align-items:center;gap:12px;">
            @isset($currentRoom)
                <span style="font-size:24px;">{{ $currentRoom->icono ?? '💬' }}</span>
                <div>
                    <h2 style="font-family:'Outfit',sans-serif;font-size:15px;font-weight:700;color:var(--hub-text);">
                        {{ $currentRoom->nombre }}
                    </h2>
                    <p style="font-size:11.5px;color:var(--hub-text-muted);">{{ $currentRoom->descripcion }}</p>
                </div>
                <div style="margin-left:auto;display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--hub-text-muted);">
                    <div class="hub-online-dot"></div>
                    En vivo
                </div>
            @else
                <h2 style="font-family:'Outfit',sans-serif;font-size:15px;font-weight:700;">Selecciona una sala</h2>
            @endisset
        </div>

        @isset($currentRoom)
            {{-- Mensajes --}}
            <div class="hub-messages-area" id="messagesArea">
                @forelse($messages as $msg)
                    @php $isOwn = $msg->user_id === Auth::id(); @endphp
                    <div class="hub-message {{ $isOwn ? 'own' : '' }}" id="msg-{{ $msg->id }}">

                        {{-- Avatar --}}
                        <div class="hub-avatar hub-avatar-sm">
                            @if($msg->user?->avatar)
                                <img src="{{ $msg->user->avatar }}" alt="">
                            @else
                                {{ strtoupper(substr($msg->user?->nombre ?? 'U', 0, 1)) }}
                            @endif
                        </div>

                        {{-- Burbuja --}}
                        <div class="hub-message-bubble">
                            {{-- Meta (nombre + hora) --}}
                            <div class="hub-message-meta">
                                @if(!$isOwn)
                                    <span class="hub-message-name">{{ $msg->user?->nombre ?? 'Usuario' }}</span>
                                @endif
                                <span>{{ $msg->created_at->format('H:i') }}</span>
                            </div>

                            {{-- Burbuja de contenido --}}
                            <div class="hub-message-content">

                                {{-- ★ Cita del mensaje original (si es respuesta) --}}
                                @if($msg->replyTo)
                                    <a class="hub-reply-quote"
                                       href="#msg-{{ $msg->replyTo->id }}"
                                       onclick="scrollToMsg({{ $msg->replyTo->id }}, event)">
                                        <span class="hub-reply-quote-author">
                                            ↩ {{ $msg->replyTo->user?->nombre ?? 'Usuario' }}
                                        </span>
                                        <span class="hub-reply-quote-text">
                                            {{ Str::limit($msg->replyTo->contenido, 80) }}
                                        </span>
                                    </a>
                                @endif

                                {{ $msg->contenido }}
                            </div>

                            @if($msg->url_adjunto)
                                <a href="{{ $msg->url_adjunto }}" target="_blank" rel="noopener"
                                   class="hub-message-url-preview">
                                    🔗 {{ $msg->url_adjunto }}
                                </a>
                            @endif
                        </div>

                        {{-- ★ Botón Responder (visible en hover) --}}
                        <button class="hub-reply-btn"
                                title="Responder"
                                onclick="setReply({{ $msg->id }}, '{{ addslashes($msg->user?->nombre ?? 'Usuario') }}', '{{ addslashes(Str::limit($msg->contenido, 80)) }}')">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="hub-empty" style="margin:auto;">
                        <div class="hub-empty-icon">👋</div>
                        <div class="hub-empty-title">¡Sé el primero en escribir!</div>
                        <div class="hub-empty-desc">Inicia la conversación en esta sala.</div>
                    </div>
                @endforelse
            </div>

            {{-- ★ Banner "Respondiendo a..." --}}
            <div class="hub-reply-preview" id="replyPreview">
                <div class="hub-reply-preview-info">
                    <div class="hub-reply-preview-author" id="replyPreviewAuthor">↩ Respondiendo a...</div>
                    <div class="hub-reply-preview-text" id="replyPreviewText"></div>
                </div>
                <button class="hub-reply-cancel" id="replyCancelBtn" onclick="cancelReply()" title="Cancelar">×</button>
            </div>

            {{-- Input de mensaje --}}
            <div class="hub-chat-input-area">
                <div class="hub-avatar hub-avatar-sm" style="flex-shrink:0;">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="">
                    @else
                        {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                    @endif
                </div>
                <input type="text"
                       class="hub-chat-input"
                       id="chatInput"
                       placeholder="Escribe un mensaje en {{ $currentRoom->nombre }}..."
                       maxlength="1000"
                       autocomplete="off">
                <button class="hub-chat-send-btn" id="sendBtn" onclick="sendMessage()" title="Enviar">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        @else
            {{-- Estado vacío si no hay sala seleccionada --}}
            <div class="hub-empty" style="margin:auto;">
                <div class="hub-empty-icon">💬</div>
                <div class="hub-empty-title">Selecciona una sala del menú</div>
                <div class="hub-empty-desc">Elige entre las salas disponibles para chatear.</div>
            </div>
        @endisset
    </div>
</div>

@isset($currentRoom)
{{-- ══ SCRIPTS DE CHAT CON REVERB ══ --}}
@vite(['resources/js/hub-chat.js'])
<script>
    // Variables globales para hub-chat.js
    window.HUB_CHAT = {
        roomSlug: '{{ $currentRoom->slug }}',
        roomId:   {{ $currentRoom->id }},
        userId:   {{ Auth::id() }},
        userName: '{{ Auth::user()->nombre }}',
        userAvatar: '{{ Auth::user()->avatar ?? '' }}',
        sendUrl: '{{ route("hub.chat.store", $currentRoom) }}',
        CSRF: '{{ csrf_token() }}',
        REVERB_APP_KEY:  '{{ config("reverb.apps.0.key") }}',
        REVERB_HOST:     '{{ config("reverb.servers.reverb.host") }}',
        REVERB_PORT:     {{ config("reverb.servers.reverb.port") }},
        REVERB_SCHEME:   '{{ config("reverb.servers.reverb.scheme") }}',
    };
</script>
@endisset

</x-hub-layout>
