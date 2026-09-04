/**
 * hub-chat.js
 * Chat en tiempo real con Laravel Echo + Laravel Reverb (WebSockets)
 * Incluye funcionalidad de Responder a mensajes (estilo WhatsApp)
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

// ── Estado de reply ─────────────────────────────────────────────
let replyingTo = null; // { id, authorName, text }

const replyPreview     = document.getElementById('replyPreview');
const replyPreviewAuth = document.getElementById('replyPreviewAuthor');
const replyPreviewText = document.getElementById('replyPreviewText');

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
            reply_to:    data.reply_to ?? null,
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

    const currentReply = replyingTo; // capturar antes de limpiar
    chatInput.value = '';
    chatInput.focus();
    sendBtn.disabled = true;
    cancelReply(); // limpiar banner

    // Inserción optimista
    const tempId = `temp-${Date.now()}`;
    appendMessage({
        id:          tempId,
        contenido:   texto,
        url_adjunto: detectUrl(texto),
        tipo:        'texto',
        reply_to:    currentReply ? { id: currentReply.id, contenido: currentReply.text, user: { nombre: currentReply.authorName } } : null,
        created_at:  new Date().toISOString(),
        user:        { id: userId, nombre: userName, avatar: userAvatar },
        isOwn:       true,
        tempId,
    });
    scrollToBottom();

    const payload = { contenido: texto };
    if (currentReply) payload.reply_to_id = currentReply.id;

    fetch(sendUrl, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
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
    // Escape cancela la respuesta
    if (e.key === 'Escape') cancelReply();
});

// ── Reply: activar estado "respondiendo a..." ───────────────────
window.setReply = function (msgId, authorName, text) {
    replyingTo = { id: msgId, authorName, text };

    replyPreviewAuth.textContent = `↩ Respondiendo a ${authorName}`;
    replyPreviewText.textContent = text;
    replyPreview.classList.add('visible');

    chatInput.focus();

    // Resaltar brevemente el mensaje original
    const originalEl = document.getElementById(`msg-${msgId}`);
    if (originalEl) {
        originalEl.style.transition = 'background 0.3s';
        originalEl.style.background = 'rgba(124,58,237,0.12)';
        setTimeout(() => { originalEl.style.background = ''; }, 1200);
    }
};

// ── Reply: cancelar ─────────────────────────────────────────────
window.cancelReply = function () {
    replyingTo = null;
    replyPreview.classList.remove('visible');
    replyPreviewAuth.textContent = '↩ Respondiendo a...';
    replyPreviewText.textContent = '';
};

// ── Scroll suave al mensaje original al hacer clic en la cita ──
window.scrollToMsg = function (msgId, event) {
    event?.preventDefault();
    const el = document.getElementById(`msg-${msgId}`);
    if (!el) return;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    // Efecto de highlight temporal
    el.style.transition = 'background 0.3s';
    el.style.background = 'rgba(124,58,237,0.15)';
    setTimeout(() => { el.style.background = ''; }, 1500);
};

// ── Typing indicator (puntitos animados) ────────────────────────
let typingTimeout = null;
let isTyping = false;

chatInput?.addEventListener('input', () => {
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => { isTyping = false; }, 2000);
    isTyping = true;
});

// ── Helpers ─────────────────────────────────────────────────────
function appendMessage({ id, contenido, url_adjunto, reply_to, created_at, user, isOwn, tempId, echoId }) {
    const msgId = tempId || echoId || `msg-${id}`;
    const time  = new Date(created_at).toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });

    const initial = (user.nombre ?? 'U').charAt(0).toUpperCase();
    const avatarHtml = user.avatar
        ? `<img src="${user.avatar}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
        : initial;

    const urlPreview = url_adjunto
        ? `<a href="${url_adjunto}" target="_blank" rel="noopener" class="hub-message-url-preview">🔗 ${url_adjunto}</a>`
        : '';

    // ★ Cita del mensaje original
    const quoteHtml = reply_to
        ? `<a class="hub-reply-quote"
               href="#msg-${reply_to.id}"
               onclick="scrollToMsg(${reply_to.id}, event)">
               <span class="hub-reply-quote-author">↩ ${escapeHtml(reply_to.user?.nombre ?? 'Usuario')}</span>
               <span class="hub-reply-quote-text">${escapeHtml((reply_to.contenido ?? '').substring(0, 80))}</span>
           </a>`
        : '';

    // Meta
    const metaHtml = isOwn
        ? `<div class="hub-message-meta"><span>${time}</span></div>`
        : `<div class="hub-message-meta"><span class="hub-message-name">${escapeHtml(user.nombre ?? '')}</span><span>${time}</span></div>`;

    // Botón de responder
    const replyBtnHtml = `
        <button class="hub-reply-btn" title="Responder"
                onclick="setReply(${id}, '${escapeAttr(user.nombre ?? 'Usuario')}', '${escapeAttr((contenido ?? '').substring(0, 80))}')">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
        </button>`;

    const div = document.createElement('div');
    div.id        = msgId;
    div.className = `hub-message${isOwn ? ' own' : ''}`;
    div.innerHTML = `
        <div class="hub-avatar hub-avatar-sm">${avatarHtml}</div>
        <div class="hub-message-bubble">
            ${metaHtml}
            <div class="hub-message-content">
                ${quoteHtml}
                ${escapeHtml(contenido)}
            </div>
            ${urlPreview}
        </div>
        ${replyBtnHtml}
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
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Escapa para usar en atributos onclick de strings JS
function escapeAttr(str) {
    return String(str ?? '')
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'");
}
