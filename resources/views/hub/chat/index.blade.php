<x-hub-layout title="Chat Estudiantil">

<div style="max-width:800px;margin:0 auto;">
    <div style="margin-bottom:24px;">
        <h2 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:800;margin-bottom:6px;">
            💬 Salas de Chat
        </h2>
        <p style="color:var(--hub-text-muted);font-size:13.5px;">
            Únete a una sala y conecta con compañeros en tiempo real.
        </p>
    </div>

    <div class="hub-goals-grid">
        @foreach($rooms as $room)
            <a href="{{ route('hub.chat.room', $room->slug) }}" style="text-decoration:none;">
                <div class="hub-card" style="cursor:pointer;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:48px;height:48px;background:linear-gradient(135deg,rgba(124,58,237,0.2),rgba(79,70,229,0.15));border:1px solid rgba(124,58,237,0.3);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
                            {{ $room->icono ?? '💬' }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <h3 style="font-size:14px;font-weight:700;color:var(--hub-text);margin-bottom:3px;">
                                {{ $room->nombre }}
                            </h3>
                            <p style="font-size:12px;color:var(--hub-text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $room->descripcion }}
                            </p>
                        </div>
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--hub-text-muted);flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    @if($room->ultimoMensaje)
                        <div style="margin-top:12px;padding-top:10px;border-top:1px solid var(--hub-border);display:flex;align-items:center;gap:8px;">
                            <div class="hub-online-dot"></div>
                            <span style="font-size:11.5px;color:var(--hub-text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <strong style="color:var(--hub-text-sub);">{{ $room->ultimoMensaje->user?->nombre }}:</strong>
                                {{ Str::limit($room->ultimoMensaje->contenido, 50) }}
                            </span>
                        </div>
                    @else
                        <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--hub-border);">
                            <span style="font-size:11.5px;color:var(--hub-text-muted);">Sin mensajes aún. ¡Sé el primero!</span>
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>

</x-hub-layout>
