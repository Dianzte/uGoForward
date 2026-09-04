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

    /* ===== DARK MODE — Transición del widget ===== */
    #chatbot-window,
    #chatbot-messages,
    #chatbot-typing,
    #chatbot-input-area {
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    /* Dark: ventana principal */
    .dark #chatbot-window {
        background: rgba(15, 23, 42, 0.92) !important;
        border-color: rgba(99, 102, 241, 0.2) !important;
        box-shadow: 0 25px 60px -10px rgba(0,0,0,0.5), 0 10px 30px -5px rgba(6,182,212,0.1) !important;
    }

    /* Dark: área de mensajes */
    .dark #chatbot-messages {
        background: linear-gradient(to bottom, #0f172a, #1e293b) !important;
    }

    /* Dark: burbuja del bot (estáticas, las dinámicas las maneja JS) */
    .dark #chatbot-messages > div:first-child > div:last-child {
        background: #1e293b !important;
        border-color: rgba(99,102,241,0.15) !important;
        color: #e2e8f0 !important;
    }

    /* Dark: typing indicator */
    .dark #chatbot-typing {
        background: #0f172a !important;
        border-top-color: rgba(99,102,241,0.1) !important;
    }

    .dark #chatbot-typing .bg-slate-100 {
        background: #1e293b !important;
    }

    /* Dark: input area */
    .dark #chatbot-input-area {
        background: #0f172a !important;
        border-top-color: rgba(99,102,241,0.1) !important;
    }

    .dark #chatbot-input {
        background: rgba(30, 41, 59, 0.8) !important;
        border-color: rgba(99, 102, 241, 0.25) !important;
        color: #e2e8f0 !important;
    }

    .dark #chatbot-input::placeholder {
        color: #64748b !important;
    }

    .dark #chatbot-input:focus {
        border-color: rgba(99, 102, 241, 0.5) !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15) !important;
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
                {{-- Ícono bot --}}
                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0 shadow-inner overflow-hidden border border-white/30">
                    <img src="{{ asset('media/hugo.png') }}" alt="Hugo Avatar" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-extrabold text-base leading-tight tracking-wide">Hugo</h3>
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
             class="flex-1 p-4 bg-gradient-to-b from-slate-50 to-white flex flex-col space-y-5 scroll-smooth"
             style="overflow-y: auto; min-height: 200px; max-height: min(420px, calc(100vh - 18rem));">

            {{-- Mensaje de bienvenida (BOT - izquierda) --}}
            <div class="gb-msg" style="display:flex; flex-direction:row; align-items:flex-end; gap:8px; width:100%; margin-bottom: 16px;">
                {{-- Avatar bot --}}
                <div style="width:44px; height:44px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:hidden; border:1px solid rgba(255,255,255,0.2); box-shadow:0 2px 6px rgba(99,102,241,0.3);">
                    <img src="{{ asset('media/hugo.png') }}" alt="Hugo" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div style="background:#f1f5f9; border:1px solid #e2e8f0; border-radius:16px 16px 16px 4px; padding:12px 16px; font-size:0.875rem; color:#334155; max-width:80%; text-align:left; line-height:1.55; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    ¡Hola! 👋 Soy <strong style="color:#4f46e5;">Hugo</strong>. ¿En qué te puedo ayudar hoy sobre becas o tu orientación vocacional?
                </div>
            </div>
        </div>

        {{-- ── TYPING INDICATOR ── --}}
        <div id="chatbot-typing" class="hidden px-4 py-2 bg-white flex items-center space-x-2 flex-shrink-0 border-t border-slate-100/80">
            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200" style="box-shadow:0 2px 6px rgba(99,102,241,0.3);">
                <img src="{{ asset('media/hugo.png') }}" alt="Hugo typing" class="w-full h-full object-cover">
            </div>
            <div class="bg-slate-100 rounded-2xl rounded-bl-none px-4 py-2.5 shadow-sm flex space-x-1.5 items-center">
                <span class="w-2 h-2 rounded-full gb-dot" style="background:linear-gradient(135deg,#6366f1,#06b6d4);"></span>
                <span class="w-2 h-2 rounded-full gb-dot" style="background:linear-gradient(135deg,#6366f1,#06b6d4);"></span>
                <span class="w-2 h-2 rounded-full gb-dot" style="background:linear-gradient(135deg,#6366f1,#06b6d4);"></span>
            </div>
        </div>

        {{-- ── INPUT AREA ── --}}
        <div id="chatbot-input-area" class="p-3 bg-white border-t border-slate-100 flex items-center gap-2 flex-shrink-0">
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
        aria-label="Abrir Hugo"
        class="relative w-16 h-16 rounded-full text-white flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-indigo-300/60 transition-transform duration-200 hover:scale-110 active:scale-95 border-2 border-transparent hover:border-white/50"
        style="background: linear-gradient(135deg,#6366f1,#06b6d4); box-shadow: 0 8px 25px -4px rgba(99,102,241,0.55);"
    >
        {{-- Ícono bot (botón) --}}
        <img src="{{ asset('media/hugo.png') }}" alt="Hugo" class="w-full h-full object-cover rounded-full" id="gb-icon-robot">
        {{-- Ícono X (cuando está abierto) --}}
        <div id="gb-icon-close" class="hidden absolute inset-0 bg-indigo-600 flex items-center justify-center rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
    </button>
</div>

{{-- ══════════════════════════════════════════
     LÓGICA DEL CHATBOT
══════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    // ── SVG del avatar robot (reemplazado por imagen) ──────────────────────────────

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
            toggleBtn.setAttribute('aria-label', 'Cerrar Hugo');
            setTimeout(() => inputEl.focus(), 300);
            scrollToBottom();
        };

        const closeWindow = () => {
            isOpen = false;
            windowEl.classList.remove('is-open');
            windowEl.classList.add('is-closing');
            iconRobot.classList.remove('hidden');
            iconClose.classList.add('hidden');
            toggleBtn.setAttribute('aria-label', 'Abrir Hugo');
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
                margin-bottom: 16px;
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
                // Burbuja bot — izquierda, blanco/dark con avatar
                const formattedText = formatBotText(text);
                const isDark = document.documentElement.classList.contains('dark');
                const botBg  = isDark ? '#1e293b' : '#f1f5f9';
                const botBorder = isDark ? 'rgba(99,102,241,0.15)' : '#e2e8f0';
                const botColor  = isDark ? '#e2e8f0' : '#334155';
                msgDiv.innerHTML = `
                    <div style="width:44px;height:44px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid rgba(255,255,255,0.2);box-shadow:0 2px 6px rgba(99,102,241,0.3);">
                        <img src="{{ asset('media/hugo.png') }}" alt="Hugo" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div style="
                        background: ${botBg};
                        border: 1px solid ${botBorder};
                        border-radius: 16px 16px 16px 4px;
                        padding: 12px 16px;
                        font-size: 0.875rem;
                        color: ${botColor};
                        max-width: 80%;
                        text-align: left;
                        line-height: 1.55;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                        word-break: break-word;
                        overflow-wrap: break-word;
                        transition: background 0.3s ease, color 0.3s ease;
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
                    addMessageToDOM("⚠️ " + (data.error || "No pude conectar con Hugo en este momento."), false);
                }
            } catch (error) {
                console.error("Error al conectar con Hugo:", error);
                addMessageToDOM("⚠️ No pude conectar con Hugo en este momento.", false);
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

    // ── Función expuesta para actualizar el tema del chatbot en tiempo real ──
    window.guayabotUpdateTheme = function(isDark) {
        const windowEl   = document.getElementById('chatbot-window');
        const messagesEl = document.getElementById('chatbot-messages');
        const typingEl   = document.getElementById('chatbot-typing');
        const inputArea  = document.getElementById('chatbot-input-area');
        const inputEl    = document.getElementById('chatbot-input');

        if (!windowEl) return;

        // Ventana principal
        if (isDark) {
            windowEl.style.background    = 'rgba(15, 23, 42, 0.92)';
            windowEl.style.borderColor   = 'rgba(99, 102, 241, 0.2)';
            windowEl.style.boxShadow     = '0 25px 60px -10px rgba(0,0,0,0.5), 0 10px 30px -5px rgba(6,182,212,0.1)';
        } else {
            windowEl.style.background    = 'rgba(255,255,255,0.85)';
            windowEl.style.borderColor   = 'rgba(255,255,255,0.6)';
            windowEl.style.boxShadow     = '0 25px 60px -10px rgba(99,102,241,0.3), 0 10px 30px -5px rgba(6,182,212,0.2)';
        }

        // Área de mensajes
        if (messagesEl) {
            messagesEl.style.background = isDark
                ? 'linear-gradient(to bottom, #0f172a, #1e293b)'
                : 'linear-gradient(to bottom, #f8fafc, #ffffff)';
        }

        // Input area
        if (inputArea) {
            inputArea.style.background   = isDark ? '#0f172a'  : '#ffffff';
            inputArea.style.borderTopColor = isDark ? 'rgba(99,102,241,0.1)' : 'rgba(241,245,249,0.8)';
        }

        // Input field
        if (inputEl) {
            inputEl.style.background   = isDark ? 'rgba(30,41,59,0.8)'  : '';
            inputEl.style.borderColor  = isDark ? 'rgba(99,102,241,0.25)' : '';
            inputEl.style.color        = isDark ? '#e2e8f0' : '';
        }

        // Typing indicator
        if (typingEl) {
            typingEl.style.background = isDark ? '#0f172a' : '#ffffff';
        }
    };
}());
</script>
