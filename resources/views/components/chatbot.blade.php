{{-- Widget del Chatbot GUAYABOT --}}

{{-- Estilos exclusivos del chatbot (inline para no depender de compilación) --}}
<style>
    /* ===== GUAYABOT – Custom Scrollbar ===== */
    #chatbot-messages::-webkit-scrollbar {
        width: 4px;
    }
    #chatbot-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    #chatbot-messages::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.35);
        border-radius: 99px;
    }
    #chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.6);
    }

    /* ===== Animación fade-scale para el modal ===== */
    @keyframes guayabot-in {
        from { opacity: 0; transform: scale(0.92) translateY(12px); }
        to   { opacity: 1; transform: scale(1)   translateY(0); }
    }
    @keyframes guayabot-out {
        from { opacity: 1; transform: scale(1)   translateY(0); }
        to   { opacity: 0; transform: scale(0.92) translateY(12px); }
    }
    #chatbot-window.is-open {
        animation: guayabot-in 0.28s cubic-bezier(0.34,1.56,0.64,1) forwards;
    }
    #chatbot-window.is-closing {
        animation: guayabot-out 0.22s ease-in forwards;
    }

    /* ===== Typing dots con desfase ===== */
    @keyframes gb-bounce {
        0%, 80%, 100% { transform: translateY(0);    opacity: .5; }
        40%            { transform: translateY(-5px); opacity: 1;  }
    }
    .gb-dot { animation: gb-bounce 1.2s infinite ease-in-out; }
    .gb-dot:nth-child(2) { animation-delay: 0.2s; }
    .gb-dot:nth-child(3) { animation-delay: 0.4s; }

    /* ===== Fade-in para cada mensaje nuevo ===== */
    @keyframes msg-in {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0);   }
    }
    .gb-msg { animation: msg-in 0.25s ease-out forwards; }

    /* ===== Gradiente animado del header ===== */
    #chatbot-header {
        background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
        background-size: 200% 200%;
        animation: grad-shift 6s ease infinite;
    }
    @keyframes grad-shift {
        0%   { background-position: 0% 50%;   }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%;   }
    }

    /* ===== Botón flotante pulse ring ===== */
    #chatbot-toggle-btn::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid rgba(99,102,241,0.5);
        animation: pulse-ring 2.4s cubic-bezier(0.4,0,0.6,1) infinite;
    }
    @keyframes pulse-ring {
        0%  { opacity: 1;  transform: scale(1); }
        70% { opacity: 0;  transform: scale(1.35); }
        100%{ opacity: 0;  transform: scale(1.35); }
    }
</style>

<div id="chatbot-widget" class="fixed bottom-6 left-6 z-50 font-sans flex flex-col items-start" style="max-height: calc(100vh - 3rem);">

    {{-- ══════════════════════════════════════════
         VENTANA DEL CHAT
    ══════════════════════════════════════════ --}}
    <div
        id="chatbot-window"
        class="hidden flex-col w-80 sm:w-96 mb-4 origin-bottom-left"
        style="
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px -10px rgba(99,102,241,0.3), 0 10px 30px -5px rgba(6,182,212,0.2);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.6);
            overflow: hidden;
            /* Contención total dentro del viewport */
            max-height: calc(100vh - 7rem);
        "
    >

        {{-- ── HEADER ── --}}
        <div id="chatbot-header" class="p-4 text-white flex justify-between items-center flex-shrink-0">
            <div class="flex items-center space-x-3">
                {{-- Ícono robot SVG --}}
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-6 h-6 fill-white">
                        <rect x="16" y="20" width="32" height="28" rx="6"/>
                        <rect x="28" y="10" width="8" height="10" rx="2"/>
                        <circle cx="22" cy="32" r="3" class="fill-indigo-900/40"/>
                        <circle cx="42" cy="32" r="3" class="fill-indigo-900/40"/>
                        <rect x="23" y="39" width="18" height="4" rx="2" class="fill-indigo-900/30"/>
                        <rect x="8"  y="28" width="6"  height="12" rx="3"/>
                        <rect x="50" y="28" width="6"  height="12" rx="3"/>
                        <circle cx="32" cy="10" r="3"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-base leading-tight tracking-wide">GUAYABOT</h3>
                    <p class="text-xs text-white/75 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full inline-block animate-pulse"></span>
                        Tu guía hacia el futuro
                    </p>
                </div>
            </div>
            <button id="chatbot-close-btn" aria-label="Cerrar chat"
                class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/35 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── ÁREA DE MENSAJES ── --}}
        <div id="chatbot-messages"
             class="flex-1 p-4 bg-gradient-to-b from-slate-50 to-white flex flex-col space-y-3 scroll-smooth"
             style="overflow-y: auto; min-height: 200px; max-height: min(420px, calc(100vh - 18rem));">

            {{-- Mensaje de bienvenida (BOT - izquierda) --}}
            <div class="gb-msg" style="display:flex; flex-direction:row; align-items:flex-end; gap:8px; width:100%;">
                {{-- Avatar robot --}}
                <div style="width:32px; height:32px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#6366f1,#06b6d4); box-shadow:0 2px 6px rgba(99,102,241,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" style="width:16px;height:16px;fill:white;">
                        <rect x="16" y="20" width="32" height="28" rx="6"/>
                        <rect x="28" y="10" width="8" height="10" rx="2"/>
                        <circle cx="22" cy="32" r="3" fill="rgba(49,46,129,0.4)"/>
                        <circle cx="42" cy="32" r="3" fill="rgba(49,46,129,0.4)"/>
                        <rect x="23" y="39" width="18" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                        <rect x="8"  y="28" width="6"  height="12" rx="3"/>
                        <rect x="50" y="28" width="6"  height="12" rx="3"/>
                        <circle cx="32" cy="10" r="3"/>
                    </svg>
                </div>
                <div style="background:#fff; border:1px solid #f1f5f9; border-radius:18px 18px 18px 4px; padding:10px 14px; font-size:0.875rem; color:#334155; max-width:80%; text-align:left; line-height:1.55; box-shadow:0 2px 8px rgba(0,0,0,0.07);">
                    ¡Hola! 👋 Soy <strong style="color:#6366f1;">GUAYABOT</strong>. ¿En qué te puedo ayudar hoy sobre becas o tu orientación vocacional?
                </div>
            </div>
        </div>

        {{-- ── TYPING INDICATOR ── --}}
        <div id="chatbot-typing" class="hidden px-4 py-2 bg-white flex items-center space-x-2 flex-shrink-0 border-t border-slate-100/80">
            <div class="w-7 h-7 rounded-xl flex-shrink-0 flex items-center justify-center"
                 style="background: linear-gradient(135deg,#6366f1,#06b6d4);">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-3.5 h-3.5 fill-white">
                    <rect x="16" y="20" width="32" height="28" rx="6"/>
                    <rect x="28" y="10" width="8" height="10" rx="2"/>
                    <circle cx="22" cy="32" r="3" class="fill-indigo-900/40"/>
                    <circle cx="42" cy="32" r="3" class="fill-indigo-900/40"/>
                    <rect x="8"  y="28" width="6"  height="12" rx="3"/>
                    <rect x="50" y="28" width="6"  height="12" rx="3"/>
                    <circle cx="32" cy="10" r="3"/>
                </svg>
            </div>
            <div class="bg-slate-100 rounded-2xl rounded-bl-none px-4 py-2.5 shadow-sm flex space-x-1.5 items-center">
                <span class="w-2 h-2 rounded-full gb-dot" style="background:linear-gradient(135deg,#6366f1,#06b6d4);"></span>
                <span class="w-2 h-2 rounded-full gb-dot" style="background:linear-gradient(135deg,#6366f1,#06b6d4);"></span>
                <span class="w-2 h-2 rounded-full gb-dot" style="background:linear-gradient(135deg,#6366f1,#06b6d4);"></span>
            </div>
        </div>

        {{-- ── INPUT AREA ── --}}
        <div class="p-3 bg-white border-t border-slate-100 flex items-center gap-2 flex-shrink-0">
            <input
                type="text"
                id="chatbot-input"
                placeholder="Escribe tu mensaje…"
                autocomplete="off"
                class="flex-1 bg-slate-50 border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 rounded-full px-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition-all duration-200"
                style="min-width:0;"
            >
            <button
                id="chatbot-send-btn"
                aria-label="Enviar mensaje"
                class="flex-shrink-0 w-10 h-10 rounded-full text-white flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 disabled:opacity-40 disabled:cursor-not-allowed hover:scale-105 active:scale-95"
                style="background: linear-gradient(135deg,#6366f1,#06b6d4); box-shadow: 0 4px 14px rgba(99,102,241,0.45);"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         BOTÓN FLOTANTE
    ══════════════════════════════════════════ --}}
    <button
        id="chatbot-toggle-btn"
        aria-label="Abrir GUAYABOT"
        class="relative w-14 h-14 rounded-full text-white flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-indigo-300/60 transition-transform duration-200 hover:scale-110 active:scale-95"
        style="background: linear-gradient(135deg,#6366f1,#06b6d4); box-shadow: 0 8px 25px -4px rgba(99,102,241,0.55);"
    >
        {{-- Ícono robot (botón) --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-7 h-7 fill-white" id="gb-icon-robot">
            <rect x="16" y="20" width="32" height="28" rx="6"/>
            <rect x="28" y="10" width="8" height="10" rx="2"/>
            <circle cx="22" cy="32" r="3" class="fill-indigo-900/50"/>
            <circle cx="42" cy="32" r="3" class="fill-indigo-900/50"/>
            <rect x="23" y="39" width="18" height="4" rx="2" class="fill-white/40"/>
            <rect x="8"  y="28" width="6"  height="12" rx="3"/>
            <rect x="50" y="28" width="6"  height="12" rx="3"/>
            <circle cx="32" cy="10" r="3"/>
        </svg>
        {{-- Ícono X (cuando está abierto) --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden absolute" id="gb-icon-close" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- ══════════════════════════════════════════
     LÓGICA DEL CHATBOT
══════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    // ── SVG del avatar robot (reutilizable) ──────────────────────────────
    const ROBOT_SVG = `
        <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center shadow-sm"
             style="background: linear-gradient(135deg,#6366f1,#06b6d4);">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-4 h-4 fill-white">
                <rect x="16" y="20" width="32" height="28" rx="6"/>
                <rect x="28" y="10" width="8" height="10" rx="2"/>
                <circle cx="22" cy="32" r="3" fill="rgba(49,46,129,0.4)"/>
                <circle cx="42" cy="32" r="3" fill="rgba(49,46,129,0.4)"/>
                <rect x="23" y="39" width="18" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                <rect x="8"  y="28" width="6"  height="12" rx="3"/>
                <rect x="50" y="28" width="6"  height="12" rx="3"/>
                <circle cx="32" cy="10" r="3"/>
            </svg>
        </div>`;

    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn  = document.getElementById('chatbot-toggle-btn');
        const closeBtn   = document.getElementById('chatbot-close-btn');
        const windowEl   = document.getElementById('chatbot-window');
        const messagesEl = document.getElementById('chatbot-messages');
        const inputEl    = document.getElementById('chatbot-input');
        const sendBtn    = document.getElementById('chatbot-send-btn');
        const typingEl   = document.getElementById('chatbot-typing');
        const iconRobot  = document.getElementById('gb-icon-robot');
        const iconClose  = document.getElementById('gb-icon-close');

        const csrfToken = "{{ csrf_token() }}";
        let chatHistory = [];
        let isOpen = false;

        // ── Abrir / Cerrar con animación ─────────────────────────────────
        const openWindow = () => {
            isOpen = true;
            windowEl.classList.remove('hidden', 'is-closing');
            windowEl.classList.add('flex', 'is-open');
            iconRobot.classList.add('hidden');
            iconClose.classList.remove('hidden');
            toggleBtn.setAttribute('aria-label', 'Cerrar GUAYABOT');
            setTimeout(() => inputEl.focus(), 300);
            scrollToBottom();
        };

        const closeWindow = () => {
            isOpen = false;
            windowEl.classList.remove('is-open');
            windowEl.classList.add('is-closing');
            iconRobot.classList.remove('hidden');
            iconClose.classList.add('hidden');
            toggleBtn.setAttribute('aria-label', 'Abrir GUAYABOT');
            windowEl.addEventListener('animationend', () => {
                if (!isOpen) {
                    windowEl.classList.add('hidden');
                    windowEl.classList.remove('flex', 'is-closing');
                }
            }, { once: true });
        };

        const toggleWindow = () => {
            if (windowEl.classList.contains('hidden') || windowEl.classList.contains('is-closing')) {
                openWindow();
            } else {
                closeWindow();
            }
        };

        // ── Scroll automático ─────────────────────────────────────────────
        const scrollToBottom = () => {
            requestAnimationFrame(() => {
                messagesEl.scrollTop = messagesEl.scrollHeight;
            });
        };

        const showTyping = () => {
            typingEl.classList.remove('hidden');
            typingEl.classList.add('flex');
            scrollToBottom();
        };
        const hideTyping = () => {
            typingEl.classList.add('hidden');
            typingEl.classList.remove('flex');
        };

        const escapeHTML = (str) =>
            str.replace(/[&<>'"]/g, tag => (
                {'&':'&amp;','<':'&lt;','>':'&gt;',"'":"&#39;",'"':'&quot;'}[tag] || tag
            ));

        const formatBotText = (text) => {
            let html = escapeHTML(text);
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            html = html.replace(/\n/g, '<br>');
            return html;
        };

        const addMessageToDOM = (text, isUser = false) => {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'gb-msg';
            msgDiv.style.cssText = `
                display: flex;
                align-items: flex-end;
                gap: 8px;
                width: 100%;
                flex-direction: ${isUser ? 'row-reverse' : 'row'};
            `;

            if (isUser) {
                // Burbuja usuario — derecha, degradado azul/cian
                msgDiv.innerHTML = `
                    <div style="
                        background: linear-gradient(135deg,#6366f1,#06b6d4);
                        box-shadow: 0 4px 16px rgba(99,102,241,0.35);
                        border-radius: 18px 18px 4px 18px;
                        padding: 10px 14px;
                        font-size: 0.875rem;
                        color: #fff;
                        max-width: 80%;
                        text-align: left;
                        line-height: 1.55;
                        word-break: break-word;
                        overflow-wrap: break-word;
                        white-space: pre-wrap;
                    ">${escapeHTML(text)}</div>`;
            } else {
                // Burbuja bot — izquierda, blanco con avatar
                const formattedText = formatBotText(text);
                msgDiv.innerHTML = `
                    <div style="width:32px;height:32px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#6366f1,#06b6d4);box-shadow:0 2px 6px rgba(99,102,241,0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" style="width:16px;height:16px;fill:white;">
                            <rect x="16" y="20" width="32" height="28" rx="6"/>
                            <rect x="28" y="10" width="8" height="10" rx="2"/>
                            <circle cx="22" cy="32" r="3" fill="rgba(49,46,129,0.4)"/>
                            <circle cx="42" cy="32" r="3" fill="rgba(49,46,129,0.4)"/>
                            <rect x="23" y="39" width="18" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                            <rect x="8"  y="28" width="6"  height="12" rx="3"/>
                            <rect x="50" y="28" width="6"  height="12" rx="3"/>
                            <circle cx="32" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div style="
                        background: #fff;
                        border: 1px solid #f1f5f9;
                        border-radius: 18px 18px 18px 4px;
                        padding: 10px 14px;
                        font-size: 0.875rem;
                        color: #334155;
                        max-width: 80%;
                        text-align: left;
                        line-height: 1.55;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
                        word-break: break-word;
                        overflow-wrap: break-word;
                    ">${formattedText}</div>`;
            }

            messagesEl.appendChild(msgDiv);
            scrollToBottom();
        };

        // ── Enviar mensaje ────────────────────────────────────────────────
        const sendMessage = async () => {
            const text = inputEl.value.trim();
            if (!text) return;

            inputEl.value = '';
            inputEl.disabled = true;
            sendBtn.disabled = true;

            addMessageToDOM(text, true);
            showTyping();

            try {
                const response = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: text, history: chatHistory })
                });

                const data = await response.json();

                if (response.ok) {
                    chatHistory.push({ role: 'user',  text: text });
                    chatHistory.push({ role: 'model', text: data.reply });
                    addMessageToDOM(data.reply, false);
                } else {
                    addMessageToDOM("⚠️ " + (data.error || "No pude conectar con GUAYABOT en este momento."), false);
                }
            } catch (error) {
                console.error("Error al conectar con GUAYABOT:", error);
                addMessageToDOM("⚠️ No pude conectar con GUAYABOT en este momento.", false);
            } finally {
                hideTyping();
                inputEl.disabled = false;
                sendBtn.disabled = false;
                inputEl.focus();
            }
        };

        // ── Event Listeners ───────────────────────────────────────────────
        toggleBtn.addEventListener('click', toggleWindow);
        closeBtn.addEventListener('click',  toggleWindow);
        sendBtn.addEventListener('click',   sendMessage);
        inputEl.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); sendMessage(); }
        });
    });
}());
</script>
