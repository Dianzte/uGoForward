<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendario de Becas y Agenda  uGoForward</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Syne:wght@400..800&display=swap" rel="stylesheet">

    <!-- Tailwind & Custom Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/css/calendario.css', 'resources/js/calendario.js'])
    
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen relative overflow-x-hidden">

    <!-- Ambient Background Effects -->
    <div class="ambient-bg">
        <div class="ambient-orb-1"></div>
        <div class="ambient-orb-2"></div>
    </div>

    <!-- NAVBAR INTEGRACIÓN UGF -->
    <header class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-40 backdrop-blur-md bg-slate-900/90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 text-white font-bold text-xl tracking-tight font-heading group">
                    <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 bg-clip-text text-transparent text-2xl group-hover:scale-105 transition-transform">UGF</span>
                    <span class="text-xs bg-slate-800 text-yellow-400 px-2 py-0.5 rounded-full border border-cyan-500/20">uGoForward</span>
                </a>
                <span class="text-slate-600 hidden sm:inline">|</span>
                <nav class="hidden sm:flex items-center space-x-3 text-xs font-medium text-slate-400">
                    <a href="{{ url('/') }}" class="hover:text-yellow-400 transition">Inicio</a>
                    <span>/</span>
                    <a href="{{ route('becas.index') }}" class="hover:text-yellow-400 transition">Becas</a>
                    <span>/</span>
                    <span class="text-slate-200">Calendario</span>
                </nav>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('becas.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-700 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    ← Volver a Becas
                </a>
            </div>
        </div>
    </header>

    <!-- BANNER DE ENCABEZADO CON ESTADÍSTICAS -->
    <section class="calendar-banner text-white py-8 px-4 sm:px-6 lg:px-8 shadow-inner">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <div class="inline-flex items-center space-x-2 bg-blue-500/20 text-yellow-300 border border-cyan-500/30 text-xs px-3 py-1 rounded-full mb-3 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-yellow-400 animate-ping"></span>
                    <span>Agenda Académica y Convocatorias</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight text-white">
                    Calendario de Becas & Tareas
                </h1>
                <p class="text-slate-300 text-sm sm:text-base mt-1 max-w-xl">
                    Visualiza los plazos de cierre, organiza tus documentos y gestiona tu agenda personal de postulación en un solo lugar.
                </p>
            </div>

            <!-- TARJETAS DE ESTADÍSTICAS RÁPIDAS -->
            <div class="grid grid-cols-3 gap-3 sm:gap-4 shrink-0">
                <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700/60 rounded-xl p-3 text-center min-w-[90px]">
                    <span id="stat-becas-count" class="block text-2xl font-bold font-heading text-cyan-400">0</span>
                    <span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Becas</span>
                </div>
                <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700/60 rounded-xl p-3 text-center min-w-[90px]">
                    <span id="stat-tareas-count" class="block text-2xl font-bold font-heading text-blue-400">0</span>
                    <span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Tareas</span>
                </div>
                <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700/60 rounded-xl p-3 text-center min-w-[90px]">
                    <span id="stat-urgentes-count" class="block text-2xl font-bold font-heading text-rose-400">0</span>
                    <span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Por cerrar</span>
                </div>
            </div>
        </div>
    </section>

    <!-- LAYOUT PRINCIPAL -->
    <div class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- BARRA LATERAL: Filtros y Leyenda -->
        <aside class="lg:col-span-1 space-y-6">
            
            <!-- Botón de Acción Principal -->
            <button id="btn-nueva-tarea" class="w-full btn-ugf-primary font-bold py-3 px-4 rounded-xl text-sm flex items-center justify-center space-x-2 tracking-wide group">
                <span class="text-lg leading-none group-hover:scale-125 transition-transform">+</span>
                <span>Agregar Tarea a Agenda</span>
            </button>

            <!-- Leyenda del Semáforo -->
            <div class="glass-card glass-card-hover p-5 rounded-2xl">
                <h3 class="font-bold text-slate-800 text-xs tracking-wider uppercase font-heading mb-4 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    <span>Estado de Convocatorias</span>
                </h3>
                <div class="space-y-3 text-xs">
                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-100/60 transition">
                        <span class="status-dot status-dot-emerald shrink-0"></span>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="font-semibold text-slate-700">Más de 15 días</span>
                            <span class="text-[10px] text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full font-bold">+15d</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-100/60 transition">
                        <span class="status-dot status-dot-amber shrink-0"></span>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="font-semibold text-slate-700">Próximo a cerrar</span>
                            <span class="text-[10px] text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full font-bold">≤15d</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-100/60 transition">
                        <span class="status-dot status-dot-rose shrink-0"></span>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="font-semibold text-slate-700">Últimos días</span>
                            <span class="text-[10px] text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full font-bold">≤5d</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-100/60 transition">
                        <span class="status-dot status-dot-blue shrink-0"></span>
                        <div class="flex-1 flex justify-between items-center">
                            <span class="font-semibold text-slate-700">Tarea Personal</span>
                            <span class="text-[10px] text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full font-bold">Agenda</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros Rápidos -->
            <div class="glass-card glass-card-hover p-5 rounded-2xl">
                <h3 class="font-bold text-slate-800 text-xs tracking-wider uppercase font-heading mb-4 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Filtros de Beca</span>
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nivel de Estudio</label>
                        <div class="relative">
                            <select id="filtro-nivel" class="w-full text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl p-3 pr-8 shadow-xs focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none appearance-none cursor-pointer transition">
                                <option value="">Todos los niveles</option>
                                <option value="grado">🎓 Pregrado / Licenciatura</option>
                                <option value="maestria">📜 Maestría / Posgrado</option>
                                <option value="doctorado">🔬 Doctorado / PhD</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Consejos -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white p-5 rounded-2xl shadow-md border border-indigo-800/50">
                <div class="flex items-center space-x-2 text-yellow-400 text-xs font-bold font-heading uppercase mb-2">
                    <span>💡 Tip </span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Consulta el calendario semanalmente y <strong class="text-yellow-300">planifica tus actividades</strong> en la agenda para cumplir cada requisito a tiempo.
                </p>
            </div>

        </aside>

        <!-- ÁREA PRINCIPAL: Calendario -->
        <main class="lg:col-span-3 glass-card p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div id="calendar"></div>
        </main>

    </div>

    <!-- MODAL: Detalle de Beca / Editar y Eliminar Tarea -->
    <div id="modal-beca" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all animate-modal-in border border-slate-100">
            
            <!-- Cabecera del Modal -->
            <div class="p-6 bg-gradient-to-r from-slate-900 to-slate-800 text-white border-b border-slate-700/50 flex justify-between items-start">
                <div>
                    <div id="modal-categoria" class="text-xs font-bold uppercase tracking-wider text-cyan-400">Beca</div>
                    <h2 id="modal-titulo" class="text-xl font-bold font-heading text-white mt-1">Cargando...</h2>
                </div>
                <button onclick="cerrarModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none transition">&times;</button>
            </div>

            <!-- VISTA BECA (Solo lectura) -->
            <div id="contenedor-beca" class="p-6 space-y-5 max-h-[60vh] overflow-y-auto custom-scrollbar hidden">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Descripción</h4>
                    <p id="modal-descripcion" class="text-sm text-slate-600 bg-slate-50 p-3.5 rounded-xl border border-slate-100 leading-relaxed">Sin información disponible.</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Checklist de Postulación</h4>
                    <div id="modal-checklist" class="space-y-2"></div>
                </div>
            </div>

            <!-- VISTA TAREA PERSONAL (Editar / Eliminar) -->
            <form id="form-editar-tarea" onsubmit="return false;" class="p-6 space-y-4 hidden">
                <input type="hidden" id="editar-tarea-id">

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Título de la Tarea</label>
                    <input type="text" id="editar-tarea-titulo" required class="w-full border-slate-200 rounded-xl p-3 border text-sm text-slate-800 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition shadow-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Fecha de la Tarea</label>
                    <input type="date" id="editar-tarea-fecha" required class="w-full border-slate-200 rounded-xl p-3 border text-sm text-slate-800 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition shadow-xs">
                </div>
            </form>

            <!-- Pie del Modal -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <button id="btn-eliminar-tarea" type="button" class="hidden px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-xl transition">
                    🗑️ Eliminar Tarea
                </button>

                <div class="flex space-x-2 ml-auto">
                    <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition">Cerrar</button>
                    <button id="btn-guardar-editar-tarea" type="button" class="hidden btn-ugf-primary px-4 py-2 text-xs font-bold rounded-xl transition">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Crear Nueva Tarea -->
    <div id="modal-crear-tarea" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-modal-in border border-slate-100">
            
            <div class="p-6 bg-gradient-to-r from-slate-900 to-indigo-950 text-white border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">📌</span>
                    <h3 class="text-lg font-bold font-heading text-white">Nueva Tarea Personal</h3>
                </div>
                <button onclick="cerrarModalCrear()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none transition">&times;</button>
            </div>

            <form id="form-crear-tarea" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Título de la Tarea</label>
                    <input type="text" id="tarea-titulo" required placeholder="Ej. Solicitar récord de notas a la U" class="w-full border-slate-200 rounded-xl p-3 border text-sm text-slate-800 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition shadow-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Fecha Programada</label>
                    <input type="date" id="tarea-fecha" required class="w-full border-slate-200 rounded-xl p-3 border text-sm text-slate-800 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition shadow-xs">
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancelar</button>
                    <button type="submit" class="btn-ugf-primary px-5 py-2.5 text-xs font-bold rounded-xl transition">Guardar Tarea</button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>