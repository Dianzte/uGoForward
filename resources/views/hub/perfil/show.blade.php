<x-hub-layout :title="$user->nombre . ' — Perfil'">

{{-- ══ BANNER + HERO ══ --}}
<div style="margin:-24px -24px 0;position:relative;margin-bottom:0;">

    {{-- Banner --}}
    <div style="height:180px;background:{{ $user->banner ? 'url('.$user->banner.') center/cover no-repeat' : 'linear-gradient(135deg,#2D1B69 0%,#1E3A5F 40%,#0F172A 100%)' }};position:relative;overflow:hidden;">
        {{-- Decoración de gradiente sobre el banner --}}
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(8,11,20,0.9));"></div>

        @if($esPropio)
            <button onclick="document.getElementById('editarPerfil').style.display='flex'"
                    style="position:absolute;top:12px;right:16px;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:6px 14px;color:white;font-size:12.5px;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(124,58,237,0.6)'"
                    onmouseout="this.style.background='rgba(0,0,0,0.5)'">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar perfil
            </button>
        @endif
    </div>

    {{-- Avatar + info del usuario --}}
    <div style="padding:0 28px;position:relative;margin-top:-50px;display:flex;align-items:flex-end;gap:18px;flex-wrap:wrap;padding-bottom:0;">
        {{-- Avatar grande --}}
        <div style="width:96px;height:96px;border-radius:50%;border:4px solid var(--hub-bg);overflow:hidden;background:linear-gradient(135deg,var(--hub-violet),var(--hub-indigo));display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:white;flex-shrink:0;box-shadow:0 0 0 2px var(--hub-violet-glow),0 8px 30px rgba(0,0,0,0.5);">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->nombre }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                {{ strtoupper(substr($user->nombre, 0, 1)) }}
            @endif
        </div>

        <div style="padding-bottom:8px;flex:1;min-width:0;">
            <h1 style="font-family:'Outfit',sans-serif;font-size:20px;font-weight:800;color:var(--hub-text);margin-bottom:2px;">
                {{ $user->nombre }}
            </h1>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                @if($user->usuario)
                    <span style="color:var(--hub-text-muted);font-size:13px;">{{ Auth::user()->usuario }}</span>
                @endif
                @if($user->departamento)
                    <span class="hub-badge hub-badge-violet" style="font-size:11px;">📍 {{ $user->departamento }}</span>
                @endif
                <span class="hub-badge hub-badge-cyan" style="font-size:11px;">🎓 Estudiante</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ BIO + STATS + CONTENIDO ══ --}}
<div style="display:flex;gap:24px;margin-top:20px;flex-wrap:wrap;">

    {{-- ── COLUMNA IZQUIERDA: Stats + Bio ── --}}
    <div style="width:260px;flex-shrink:0;display:flex;flex-direction:column;gap:14px;">

        {{-- Bio --}}
        <div class="hub-card">
            <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--hub-text-muted);margin-bottom:10px;">Acerca de</h3>
            @if($user->bio)
                <p style="font-size:13.5px;color:var(--hub-text-sub);line-height:1.65;">{{ $user->bio }}</p>
            @else
                <p style="font-size:13px;color:var(--hub-text-muted);font-style:italic;">
                    {{ $esPropio ? 'Agrega una bio para presentarte a la comunidad.' : 'Este estudiante aún no ha agregado una bio.' }}
                </p>
            @endif

            @if($esPropio)
                <button onclick="document.getElementById('editarPerfil').style.display='flex'"
                        class="hub-btn hub-btn-ghost hub-btn-sm" style="margin-top:10px;padding:5px 0;color:var(--hub-violet-light);">
                    ✏️ {{ $user->bio ? 'Editar bio' : 'Agregar bio' }}
                </button>
            @endif
        </div>

        {{-- Stats --}}
        <div class="hub-card">
            <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--hub-text-muted);margin-bottom:12px;">Estadísticas</h3>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--hub-text-sub);display:flex;align-items:center;gap:6px;">
                        <span>📝</span> Aportes publicados
                    </span>
                    <span style="font-size:14px;font-weight:700;color:var(--hub-text);">{{ $posts->count() }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--hub-text-sub);display:flex;align-items:center;gap:6px;">
                        <span>🔥</span> Upvotes recibidos
                    </span>
                    <span style="font-size:14px;font-weight:700;color:var(--hub-violet-light);">{{ $totalUpvotes }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--hub-text-sub);display:flex;align-items:center;gap:6px;">
                        <span>✅</span> Metas completadas
                    </span>
                    <span style="font-size:14px;font-weight:700;color:#34D399;">{{ $goalsCompletadas }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--hub-text-sub);display:flex;align-items:center;gap:6px;">
                        <span>🎯</span> Metas activas
                    </span>
                    <span style="font-size:14px;font-weight:700;color:#FBBF24;">{{ $goalsActivas->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Metas activas --}}
        @if($goalsActivas->count())
        <div class="hub-card">
            <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--hub-text-muted);margin-bottom:12px;">Metas Activas 🔥</h3>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($goalsActivas as $goal)
                    <div>
                        <div style="font-size:12.5px;font-weight:600;color:var(--hub-text);margin-bottom:5px;line-height:1.3;">
                            {{ Str::limit($goal->titulo, 45) }}
                        </div>
                        <div class="hub-progress-bar">
                            <div class="hub-progress-fill" style="width:{{ $goal->progreso }}%"></div>
                        </div>
                        <div style="font-size:11px;color:var(--hub-text-muted);text-align:right;margin-top:2px;">{{ $goal->progreso }}%</div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('hub.goals') }}" class="hub-btn hub-btn-ghost hub-btn-sm" style="margin-top:10px;color:var(--hub-violet-light);">
                Ver todas →
            </a>
        </div>
        @endif
    </div>

    {{-- ── COLUMNA DERECHA: Aportes publicados ── --}}
    <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h2 style="font-family:'Outfit',sans-serif;font-size:15px;font-weight:700;color:var(--hub-text);">
                📚 Aportes de {{ $user->nombre }}
            </h2>
            @if($esPropio)
                <a href="{{ route('hub.feed') }}" class="hub-btn hub-btn-primary hub-btn-sm">
                    + Nuevo aporte
                </a>
            @endif
        </div>

        @forelse($posts as $post)
            <div class="hub-card" style="margin-bottom:12px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">
                    <div style="flex:1;min-width:0;">
                        {{-- Tipo y materia --}}
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
                            @php
                                $tipoMap = ['resumen'=>['📝','violet'],'enlace'=>['🔗','cyan'],'guia'=>['📖','green'],'tip'=>['💡','orange']];
                                [$icon,$color] = $tipoMap[$post->tipo] ?? ['📄','violet'];
                            @endphp
                            <span class="hub-badge hub-badge-{{ $color }}">{{ $icon }} {{ ucfirst($post->tipo) }}</span>
                            @if($post->materia)
                                <span class="hub-badge hub-badge-violet">{{ $post->materia }}</span>
                            @endif
                            <span style="font-size:11px;color:var(--hub-text-muted);margin-left:auto;">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 style="font-size:14px;font-weight:700;color:var(--hub-text);margin-bottom:6px;line-height:1.4;">
                            {{ $post->titulo }}
                        </h3>
                        <p style="font-size:13px;color:var(--hub-text-sub);line-height:1.55;margin-bottom:10px;">
                            {{ Str::limit($post->contenido, 200) }}
                        </p>

                        {{-- Etiquetas --}}
                        @if($post->etiquetas && count($post->etiquetas))
                            <div style="display:flex;gap:5px;flex-wrap:wrap;margin-bottom:10px;">
                                @foreach($post->etiquetas as $tag)
                                    <span style="color:var(--hub-violet-light);font-size:11.5px;">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Métricas --}}
                        <div style="display:flex;align-items:center;gap:14px;">
                            <span style="font-size:12px;color:var(--hub-text-muted);display:flex;align-items:center;gap:4px;">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                {{ $post->upvotes_count }} útiles
                            </span>
                            <span style="font-size:12px;color:var(--hub-text-muted);display:flex;align-items:center;gap:4px;">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                {{ $post->comentarios_count }} comentarios
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="hub-empty">
                <div class="hub-empty-icon">📭</div>
                <div class="hub-empty-title">Sin aportes aún</div>
                <div class="hub-empty-desc">
                    {{ $esPropio ? '¡Publica tu primer aporte en el Muro Académico!' : 'Este estudiante aún no ha publicado aportes.' }}
                </div>
                @if($esPropio)
                    <a href="{{ route('hub.feed') }}" class="hub-btn hub-btn-primary" style="margin-top:16px;display:inline-flex;">
                        Ir al Feed →
                    </a>
                @endif
            </div>
        @endforelse

        @if($posts->count() >= 6)
            <div style="text-align:center;margin-top:4px;">
                <a href="{{ route('hub.feed') }}" style="font-size:13px;color:var(--hub-violet-light);text-decoration:none;">
                    Ver todos los aportes →
                </a>
            </div>
        @endif
    </div>
</div>

{{-- ══ MODAL: Editar Perfil ══ --}}
@if($esPropio)
<div id="editarPerfil"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(8px);z-index:999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:var(--hub-surface);border:1px solid var(--hub-border);border-radius:var(--hub-radius);padding:28px;max-width:500px;width:100%;animation:hub-slideUp 0.3s ease;">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-family:'Outfit',sans-serif;font-size:17px;font-weight:700;">✏️ Editar Perfil</h3>
            <button onclick="document.getElementById('editarPerfil').style.display='none'"
                    style="background:none;border:none;color:var(--hub-text-muted);cursor:pointer;font-size:22px;line-height:1;">×</button>
        </div>

        <form method="POST" action="{{ route('hub.perfil.update') }}" onsubmit="guardarPerfil(event)">
            @csrf
            @method('PATCH')

            <div class="hub-form-group">
                <label class="hub-label">Bio (máx. 300 caracteres)</label>
                <textarea name="bio" class="hub-input hub-textarea" rows="3" maxlength="300"
                          placeholder="Cuéntanos sobre ti: tu carrera, intereses académicos, universidad...">{{ $user->bio }}</textarea>
                <div style="text-align:right;font-size:11px;color:var(--hub-text-muted);margin-top:3px;" id="bioCounter">
                    {{ strlen($user->bio ?? '') }}/300
                </div>
            </div>

            <div class="hub-form-group">
                <label class="hub-label">URL del Avatar</label>
                <input type="url" name="avatar" class="hub-input"
                       placeholder="https://i.pravatar.cc/150?u=tu-email"
                       value="{{ $user->avatar_url ?? (str_starts_with($user->avatar ?? '', 'http') ? $user->avatar : '') }}">
                <p style="font-size:11.5px;color:var(--hub-text-muted);margin-top:4px;">
                    💡 Puedes usar <a href="https://gravatar.com" target="_blank" style="color:var(--hub-cyan);">Gravatar</a> o una URL de imagen pública.
                </p>
            </div>

            <div class="hub-form-group">
                <label class="hub-label">URL del Banner (encabezado)</label>
                <input type="url" name="banner" class="hub-input"
                       placeholder="https://images.unsplash.com/..."
                       value="{{ $user->banner_url ?? (str_starts_with($user->banner ?? '', 'http') ? $user->banner : '') }}">
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="hub-btn hub-btn-secondary"
                        onclick="document.getElementById('editarPerfil').style.display='none'">
                    Cancelar
                </button>
                <button type="submit" class="hub-btn hub-btn-primary" id="savePerfilBtn">
                    💾 Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Contador de caracteres en bio
document.querySelector('textarea[name="bio"]')?.addEventListener('input', function() {
    document.getElementById('bioCounter').textContent = `${this.value.length}/300`;
});

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

function guardarPerfil(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('savePerfilBtn');
    btn.disabled = true;
    btn.textContent = 'Guardando...';

    const formData = new FormData(form);

    fetch('{{ route("hub.perfil.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData,
    })
    .then(async r => {
        const data = await r.json().catch(() => ({}));
        if (!r.ok) {
            let errorMsg = 'Error al guardar los cambios.';
            if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('\n');
            } else if (data.message) {
                errorMsg = data.message;
            }
            throw new Error(errorMsg);
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            document.getElementById('editarPerfil').style.display = 'none';
            location.reload();
        }
    })
    .catch(err => {
        alert(err.message || 'Error al guardar. Intenta de nuevo.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = '💾 Guardar cambios';
    });
}
</script>
@endif

</x-hub-layout>
