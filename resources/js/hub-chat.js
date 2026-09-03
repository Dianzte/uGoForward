/**
 * hub-chat.js
 * Chat en tiempo real con Laravel Echo + Laravel Reverb (WebSockets)
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// ── Configurar Echo con Reverb ──────────────────────────────────
window.Echo = new Echo({
    broadcaster: 'reverb',
    key:         window.HUB_CHAT.REVERB_APP_KEY,
    wsHost:      window.HUB_CHAT.REVERB_HOST,
    wsPort:      window.HUB_CHAT.REVERB_PORT,
    wssPort:     window.HUB_CHAT.REVERB_PORT,
    forceTLS:    window.HUB_CHAT.REVERB_SCHEME === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

// ── Estado local del chat ───────────────────────────────────────
const { roomSlug, userId, userName, userAvatar, sendUrl, CSRF } = window.HUB_CHAT;

const messagesArea = document.getElementById('messagesArea');
const chatInput    = document.getElementById('chatInput');
const sendBtn      = document.getElementById('sendBtn');

// ── Scroll al fondo al cargar ───────────────────────────────────
scrollToBottom();

// ── Suscribirse al canal de la sala ────────────────────────────
window.Echo.channel(`chat.${roomSlug}`)
    .listen('.message.sent', (data) => {
        // Evitar duplicar los mensajes propios (ya insertados optimistamente)
        if (document.getElementById(`msg-echo-${data.id}`)) return;

        const isOwn = data.user.id === userId;
        appendMessage({
            id:          data.id,
            contenido:   data.contenido,
            url_adjunto: data.url_adjunto,
            tipo:        data.tipo,
            created_at:  data.created_at,
            user:        data.user,
            isOwn,
            echoId:      `msg-echo-${data.id}`,
        });
        scrollToBottom();
    });

// ── Enviar mensaje ──────────────────────────────────────────────
window.sendMessage = function () {
    const texto = chatInput.value.trim();
    if (!texto) return;

    chatInput.value = '';
    chatInput.focus();
    sendBtn.disabled = true;

    // Inserción optimista (antes de confirmar el servidor)
    const tempId = `temp-${Date.now()}`;
    appendMessage({
        id:         tempId,
        contenido:  texto,
        url_adjunto: detectUrl(texto),
        tipo:       'texto',
        created_at: new Date().toISOString(),
        user:       { id: userId, nombre: userName, avatar: userAvatar },
        isOwn:      true,
        tempId,
    });
    scrollToBottom();

    fetch(sendUrl, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ contenido: texto }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Reemplazar el ID temporal por el real
            const tempEl = document.getElementById(tempId);
            if (tempEl) tempEl.id = `msg-echo-${data.message.id}`;
        }
    })
    .catch(() => {
        const tempEl = document.getElementById(tempId);
        if (tempEl) {
            tempEl.style.opacity = '0.4';
            tempEl.title = 'Error al enviar';
        }
    })
    .finally(() => {
        sendBtn.disabled = false;
    });
};

// ── Enter para enviar ───────────────────────────────────────────
chatInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        window.sendMessage();
    }
});

// ── Typing indicator (puntitos animados) ────────────────────────
let typingTimeout = null;
let isTyping = false;
const typingEl = createTypingIndicator();

chatInput?.addEventListener('input', () => {
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        if (typingEl.parentNode) typingEl.remove();
        isTyping = false;
    }, 2000);

    if (!isTyping) {
        isTyping = true;
    }
});

function createTypingIndicator() {
    const wrap = document.createElement('div');
    wrap.className = 'hub-message';
    wrap.id = 'typing-indicator';
    wrap.innerHTML = `
        <div class="hub-avatar hub-avatar-sm" style="background:linear-gradient(135deg,#7C3AED,#4F46E5);">?</div>
        <div class="hub-typing-indicator">
            <div class="hub-typing-dot"></div>
            <div class="hub-typing-dot"></div>
            <div class="hub-typing-dot"></div>
        </div>`;
    return wrap;
}

// ── Helpers ─────────────────────────────────────────────────────
function appendMessage({ id, contenido, url_adjunto, tipo, created_at, user, isOwn, tempId, echoId }) {
    const msgId = tempId || echoId || `msg-${id}`;
    const time  = new Date(created_at).toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });

    const initial = (user.nombre ?? 'U').charAt(0).toUpperCase();
    const avatarHtml = user.avatar
        ? `<img src="${user.avatar}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
        : initial;

    const urlPreview = url_adjunto
        ? `<a href="${url_adjunto}" target="_blank" rel="noopener" class="hub-message-url-preview">🔗 ${url_adjunto}</a>`
        : '';

    const div = document.createElement('div');
    div.id        = msgId;
    div.className = `hub-message${isOwn ? ' own' : ''}`;
    div.style.animation = 'hub-slideUp 0.25s ease';
    div.innerHTML = `
        ${!isOwn ? `<div class="hub-avatar hub-avatar-sm">${avatarHtml}</div>` : ''}
        <div class="hub-message-bubble">
            <div class="hub-message-meta">
                ${!isOwn ? `<span class="hub-message-name">${user.nombre}</span>` : ''}
                <span>${time}</span>
            </div>
            <div class="hub-message-content">${escapeHtml(contenido)}</div>
            ${urlPreview}
        </div>
        ${isOwn ? `<div class="hub-avatar hub-avatar-sm">${avatarHtml}</div>` : ''}
    `;

    // Eliminar empty state si existe
    const emptyEl = messagesArea?.querySelector('.hub-empty');
    emptyEl?.remove();

    messagesArea?.appendChild(div);
}

function scrollToBottom() {
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
}

function detectUrl(texto) {
    const m = texto.match(/https?:\/\/[^\s]+/);
    return m ? m[0] : null;
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
