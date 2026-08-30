<x-hub-layout title="Rastreador de Metas">

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:800;margin-bottom:4px;">
            🎯 Rastreador de Metas
        </h2>
        <p style="color:var(--hub-text-muted);font-size:13.5px;">
            Define tus metas académicas y recibe apoyo de la comunidad.
        </p>
    </div>
    <button class="hub-btn hub-btn-primary" onclick="document.getElementById('metaFormModal').style.display='flex'">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Meta
    </button>
</div>

{{-- Mis Metas --}}
@if($misMetas->count())
<div style="margin-bottom:32px;">
    <h3 style="font-size:14px;font-weight:700;color:var(--hub-text-sub);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">
        Mis Metas
    </h3>
    <div class="hub-goals-grid">
        @foreach($misMetas as $goal)
            <div class="hub-card" id="goal-{{ $goal->id }}">
                {{-- Estado badge --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    @php
                        $estadoMap = [
                            'en_progreso' => ['🔥','orange','En progreso'],
                            'completada'  => ['✅','green','Completada'],
                            'abandonada'  => ['❌','pink','Abandonada'],
                        ];
                        [$eIcon,$eColor,$eLabel] = $estadoMap[$goal->estado] ?? ['🔥','orange','En progreso'];
                    @endphp
                    <span class="hub-badge hub-badge-{{ $eColor }}">{{ $eIcon }} {{ $eLabel }}</span>

                    @if($goal->es_publica)
                        <span style="font-size:11px;color:var(--hub-text-muted);">🌍 Pública</span>
                    @else
                        <span style="font-size:11px;color:var(--hub-text-muted);">🔒 Privada</span>
                    @endif
                </div>

                <h3 style="font-size:14px;font-weight:700;color:var(--hub-text);margin-bottom:6px;line-height:1.4;">
                    {{ $goal->titulo }}
                </h3>

                @if($goal->descripcion)
                    <p style="font-size:12.5px;color:var(--hub-text-muted);margin-bottom:12px;line-height:1.5;">
                        {{ $goal->descripcion }}
                    </p>
                @endif

                {{-- Barra de progreso --}}
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:11.5px;color:var(--hub-text-sub);margin-bottom:5px;">
                        <span>Progreso</span>
                        <span id="prog-label-{{ $goal->id }}">{{ $goal->progreso }}%</span>
                    </div>
                    <div class="hub-progress-bar">
                        <div class="hub-progress-fill" id="prog-fill-{{ $goal->id }}"
                             style="width:{{ $goal->progreso }}%"></div>
                    </div>
                </div>

                {{-- Slider de progreso --}}
                @if($goal->estado === 'en_progreso')
                    <div style="margin-bottom:14px;">
                        <input type="range" min="0" max="100" value="{{ $goal->progreso }}"
                               id="slider-{{ $goal->id }}"
                               style="width:100%;accent-color:var(--hub-violet-light);cursor:pointer;"
                               oninput="actualizarProgreso({{ $goal->id }}, this.value)"
                               onchange="guardarProgreso({{ $goal->id }}, this.value)">
                    </div>
                @endif

                {{-- Footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                    <div style="display:flex;gap:8px;">
                        @if($goal->estado === 'en_progreso')
                            <button class="hub-btn hub-btn-secondary hub-btn-sm"
                                    onclick="cambiarEstado({{ $goal->id }}, 'completada')">
                                ✅ Completar
                            </button>
                        @endif
                    </div>
                    <div style="font-size:11.5px;color:var(--hub-text-muted);display:flex;align-items:center;gap:4px;">
                        💪 <span id="apoyos-count-{{ $goal->id }}">{{ $goal->apoyos_count }}</span> apoyos
                    </div>
                </div>

                @if($goal->fecha_limite)
                    <div style="margin-top:8px;font-size:11.5px;color:var(--hub-text-muted);">
                        📅 Límite: {{ $goal->fecha_limite->format('d/m/Y') }}
                        @if($goal->fecha_limite->isPast() && $goal->estado === 'en_progreso')
                            <span style="color:#F87171;margin-left:4px;">⚠️ Vencida</span>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Metas de la Comunidad --}}
<div>
    <h3 style="font-size:14px;font-weight:700;color:var(--hub-text-sub);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">
        Metas de la Comunidad
    </h3>

    @if($metasComunidad->count())
        <div class="hub-goals-grid">
            @foreach($metasComunidad as $goal)
                <div class="hub-card" id="goal-com-{{ $goal->id }}">
                    {{-- Usuario --}}
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                        <div class="hub-avatar hub-avatar-sm">
                            @if($goal->user->avatar)
                                <img src="{{ $goal->user->avatar }}" alt="">
                            @else
                                {{ strtoupper(substr($goal->user->nombre, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-size:12.5px;font-weight:600;color:var(--hub-text);">{{ $goal->user->nombre }}</div>
                            <div style="font-size:11px;color:var(--hub-text-muted);">{{ $goal->created_at->diffForHumans() }}</div>
                        </div>
                        @php
                            $estadoMap = ['en_progreso'=>['🔥','orange'],'completada'=>['✅','green'],'abandonada'=>['❌','pink']];
                            [$eIcon,$eColor] = $estadoMap[$goal->estado] ?? ['🔥','orange'];
                        @endphp
                        <span class="hub-badge hub-badge-{{ $eColor }}" style="margin-left:auto;">{{ $eIcon }}</span>
                    </div>

                    <h3 style="font-size:13.5px;font-weight:700;color:var(--hub-text);margin-bottom:6px;">
                        {{ $goal->titulo }}
                    </h3>

                    @if($goal->descripcion)
                        <p style="font-size:12.5px;color:var(--hub-text-muted);margin-bottom:10px;line-height:1.5;">
                            {{ Str::limit($goal->descripcion, 100) }}
                        </p>
                    @endif

                    {{-- Progreso --}}
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--hub-text-muted);margin-bottom:4px;">
                            <span>Progreso</span>
                            <span>{{ $goal->progreso }}%</span>
                        </div>
                        <div class="hub-progress-bar">
                            <div class="hub-progress-fill" style="width:{{ $goal->progreso }}%"></div>
                        </div>
                    </div>

                    {{-- Botón de apoyo --}}
                    @php $yaApoyo = in_array($goal->id, $apoyadasIds); @endphp
                    <button class="hub-upvote-btn {{ $yaApoyo ? 'active' : '' }}"
                            style="width:100%;justify-content:center;"
                            id="apoyo-btn-{{ $goal->id }}"
                            onclick="toggleApoyo({{ $goal->id }}, this)">
                        <span>💪</span>
                        <span id="apoyo-count-{{ $goal->id }}">{{ $goal->apoyos_count }}</span>
                        {{ $yaApoyo ? '¡Apoyado!' : 'Apoyar' }}
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <div class="hub-empty">
            <div class="hub-empty-icon">🌟</div>
            <div class="hub-empty-title">La comunidad aún no tiene metas públicas</div>
            <div class="hub-empty-desc">¡Sé el primero en publicar una meta e inspirar a otros!</div>
        </div>
    @endif
</div>

{{-- ══ MODAL: Crear nueva meta ══ --}}
<div id="metaFormModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);z-index:999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:var(--hub-surface);border:1px solid var(--hub-border);border-radius:var(--hub-radius);padding:28px;max-width:480px;width:100%;animation:hub-slideUp 0.3s ease;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-family:'Outfit',sans-serif;font-size:17px;font-weight:700;">🎯 Nueva Meta Académica</h3>
            <button onclick="document.getElementById('metaFormModal').style.display='none'"
                    style="background:none;border:none;color:var(--hub-text-muted);cursor:pointer;font-size:20px;line-height:1;">×</button>
        </div>

        <form method="POST" action="{{ route('hub.goals.store') }}" onsubmit="crearMeta(event)">
            @csrf
            <div class="hub-form-group">
                <label class="hub-label">¿Cuál es tu meta?</label>
                <input type="text" name="titulo" class="hub-input" required maxlength="200"
                       placeholder="Ej: Aprobar Cálculo con nota ≥ 7">
            </div>

            <div class="hub-form-group">
                <label class="hub-label">Descripción (opcional)</label>
                <textarea name="descripcion" class="hub-input hub-textarea" rows="3" maxlength="1000"
                          placeholder="¿Qué implica lograr esta meta? ¿Cómo planeas alcanzarla?"></textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="hub-form-group">
                    <label class="hub-label">Fecha límite</label>
                    <input type="date" name="fecha_limite" class="hub-input"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                <div class="hub-form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding-bottom:12px;">
                        <input type="checkbox" name="es_publica" value="1" checked
                               style="width:16px;height:16px;accent-color:var(--hub-violet-light);">
                        <span style="font-size:13px;color:var(--hub-text-sub);">Meta pública (visible para la comunidad)</span>
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <button type="button" class="hub-btn hub-btn-secondary"
                        onclick="document.getElementById('metaFormModal').style.display='none'">
                    Cancelar
                </button>
                <button type="submit" class="hub-btn hub-btn-primary">
                    🎯 Crear Meta
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

// Actualizar label del slider en tiempo real
function actualizarProgreso(goalId, val) {
    document.getElementById(`prog-label-${goalId}`).textContent = `${val}%`;
    document.getElementById(`prog-fill-${goalId}`).style.width = `${val}%`;
}

// Guardar progreso al soltar el slider
function guardarProgreso(goalId, val) {
    fetch(`/hub/metas/${goalId}`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ progreso: parseInt(val) }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.goal.estado === 'completada') {
            // Actualizar badge si se completó automáticamente
            const card = document.getElementById(`goal-${goalId}`);
            const badge = card.querySelector('.hub-badge');
            if (badge) badge.outerHTML = '<span class="hub-badge hub-badge-green">✅ Completada</span>';
        }
    });
}

// Cambiar estado de la meta
function cambiarEstado(goalId, estado) {
    fetch(`/hub/metas/${goalId}`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ estado }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

// Toggle apoyo a una meta de la comunidad
function toggleApoyo(goalId, btn) {
    fetch(`/hub/metas/${goalId}/apoyo`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById(`apoyo-count-${goalId}`).textContent = data.apoyos_count;
        btn.classList.toggle('active', data.apoyado);
        btn.querySelector('span:last-child').textContent = data.apoyado ? '¡Apoyado!' : 'Apoyar';
    });
}

// Crear meta vía AJAX
function crearMeta(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Asegurar que es_publica se envíe aunque no esté chequeado
    if (!formData.has('es_publica')) formData.set('es_publica', '0');

    fetch('{{ route("hub.goals.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: formData,
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('metaFormModal').style.display = 'none';
            location.reload();
        }
    })
    .catch(() => alert('Error al crear la meta. Intenta de nuevo.'));
}
</script>
</x-hub-layout>
