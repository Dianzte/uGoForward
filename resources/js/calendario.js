document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    let calendar;
    let allEvents = [];
    let currentFilter = '';

    // Initialize FullCalendar
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana'
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            fetch(`/api/becas-calendario/eventos?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`)
                .then(res => res.json())
                .then(data => {
                    allEvents = data;
                    updateStatsCounters(allEvents);
                    let filtered = applyFilter(allEvents, currentFilter);
                    successCallback(filtered);
                })
                .catch(err => {
                    console.error('Error cargando eventos:', err);
                    failureCallback(err);
                });
        },
        eventClick: function (info) {
            abrirModal(info.event);
        },
        eventDidMount: function (info) {
            // Add custom tooltip or styling attributes if needed
            let tipo = info.event.extendedProps.tipo;
            if (tipo === 'tarea') {
                info.el.title = `Agenda: ${info.event.title}`;
            } else {
                info.el.title = `Beca: ${info.event.title}`;
            }
        }
    });

    calendar.render();
    window.calendarInstance = calendar;

    // Filter Listener
    let filtroNivelSelect = document.getElementById('filtro-nivel');
    if (filtroNivelSelect) {
        filtroNivelSelect.addEventListener('change', function (e) {
            currentFilter = e.target.value;
            calendar.refetchEvents();
        });
    }

    // Helper: Filter events locally
    function applyFilter(events, filterVal) {
        if (!filterVal) return events;
        return events.filter(evt => {
            // Tareas personales se muestran siempre
            if (evt.extendedProps && evt.extendedProps.tipo === 'tarea') return true;
            
            let cat = (evt.extendedProps && evt.extendedProps.categoria) ? evt.extendedProps.categoria.toLowerCase() : '';
            let title = (evt.title) ? evt.title.toLowerCase() : '';
            let desc = (evt.extendedProps && evt.extendedProps.descripcion) ? evt.extendedProps.descripcion.toLowerCase() : '';

            if (filterVal === 'grado') {
                return cat.includes('pregrado') || cat.includes('licenciatura') || cat.includes('grado') || title.includes('licenciatura');
            } else if (filterVal === 'maestria') {
                return cat.includes('maestría') || cat.includes('maestria') || cat.includes('posgrado') || cat.includes('postgrado');
            } else if (filterVal === 'doctorado') {
                return cat.includes('doctorado') || cat.includes('phd');
            }
            return true;
        });
    }

    // Update Banner Stat Counters
    function updateStatsCounters(events) {
        let becasCount = 0;
        let tareasCount = 0;
        let urgentesCount = 0;

        events.forEach(e => {
            let tipo = e.extendedProps ? e.extendedProps.tipo : '';
            if (tipo === 'tarea') {
                tareasCount++;
            } else {
                becasCount++;
                if (e.color === '#EF4444') { // Urgent closing date (<= 5 days)
                    urgentesCount++;
                }
            }
        });

        let elBecas = document.getElementById('stat-becas-count');
        let elTareas = document.getElementById('stat-tareas-count');
        let elUrgentes = document.getElementById('stat-urgentes-count');

        if (elBecas) elBecas.innerText = becasCount;
        if (elTareas) elTareas.innerText = tareasCount;
        if (elUrgentes) elUrgentes.innerText = urgentesCount;
    }

    // ==========================================
    // TAREA CRUD EVENT HANDLERS
    // ==========================================

    // 1. Abrir Modal Crear Tarea
    let btnNuevaTarea = document.getElementById('btn-nueva-tarea');
    if (btnNuevaTarea) {
        btnNuevaTarea.addEventListener('click', function () {
            let hoy = new Date().toISOString().split('T')[0];
            let fechaInput = document.getElementById('tarea-fecha');
            if (fechaInput) fechaInput.value = hoy;
            
            let modalCrear = document.getElementById('modal-crear-tarea');
            if (modalCrear) modalCrear.classList.remove('hidden');
        });
    }

    // 2. Submit Crear Tarea
    let formCrearTarea = document.getElementById('form-crear-tarea');
    if (formCrearTarea) {
        formCrearTarea.addEventListener('submit', function (e) {
            e.preventDefault();

            let titulo = document.getElementById('tarea-titulo').value;
            let fecha = document.getElementById('tarea-fecha').value;
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/api/calendario/tareas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ titulo: titulo, fecha: fecha })
            })
                .then(async res => {
                    let data = await res.json().catch(() => ({}));
                    if (res.ok && data.success) {
                        cerrarModalCrear();
                        formCrearTarea.reset();
                        calendar.refetchEvents();
                        mostrarToast('✅ Tarea agregada exitosamente', 'success');
                    } else {
                        let errMsg = data.error || 'Error al guardar la tarea';
                        mostrarToast(`❌ ${errMsg}`, 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    mostrarToast('❌ No se pudo guardar la tarea', 'error');
                });
        });
    }

    // 3. Modificar Tarea
    let btnGuardarEditar = document.getElementById('btn-guardar-editar-tarea');
    if (btnGuardarEditar) {
        btnGuardarEditar.addEventListener('click', function () {
            let id = document.getElementById('editar-tarea-id').value;
            let titulo = document.getElementById('editar-tarea-titulo').value;
            let fecha = document.getElementById('editar-tarea-fecha').value;
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!titulo || !fecha) {
                mostrarToast('⚠️ Completa todos los campos', 'warning');
                return;
            }

            fetch(`/api/calendario/tareas/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ titulo: titulo, fecha: fecha })
            })
                .then(async res => {
                    let data = await res.json().catch(() => ({}));
                    if (res.ok && data.success) {
                        cerrarModal();
                        calendar.refetchEvents();
                        mostrarToast('✅ Tarea actualizada exitosamente', 'success');
                    } else {
                        let errMsg = data.error || 'Error al actualizar tarea';
                        mostrarToast(`❌ ${errMsg}`, 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    mostrarToast('❌ Error de comunicación con el servidor', 'error');
                });
        });
    }

    // 4. Eliminar Tarea
    let btnEliminar = document.getElementById('btn-eliminar-tarea');
    if (btnEliminar) {
        btnEliminar.addEventListener('click', function () {
            let id = document.getElementById('editar-tarea-id').value;
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!confirm('¿Estás seguro de eliminar esta tarea de tu agenda?')) return;

            fetch(`/api/calendario/tareas/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(async res => {
                    let data = await res.json().catch(() => ({}));
                    if (res.ok && data.success) {
                        cerrarModal();
                        calendar.refetchEvents();
                        mostrarToast('🗑️ Tarea eliminada', 'info');
                    } else {
                        let errMsg = data.error || 'Error al eliminar la tarea';
                        mostrarToast(`❌ ${errMsg}`, 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    mostrarToast('❌ No se pudo eliminar la tarea', 'error');
                });
        });
    }
});

// Global Modal Functions
window.abrirModal = function (evento) {
    let tipo = evento.extendedProps ? evento.extendedProps.tipo : '';

    let contenedorBeca = document.getElementById('contenedor-beca');
    let formEditarTarea = document.getElementById('form-editar-tarea');
    let btnEliminar = document.getElementById('btn-eliminar-tarea');
    let btnGuardarEditar = document.getElementById('btn-guardar-editar-tarea');
    let modalBeca = document.getElementById('modal-beca');

    if (!modalBeca) return;

    if (tipo === 'tarea') {
        // Modo Tarea Personal
        document.getElementById('modal-categoria').innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">📌 Agenda Personal</span>';
        document.getElementById('modal-titulo').innerText = 'Editar Tarea Personal';

        if (contenedorBeca) contenedorBeca.classList.add('hidden');
        if (formEditarTarea) formEditarTarea.classList.remove('hidden');
        if (btnEliminar) btnEliminar.classList.remove('hidden');
        if (btnGuardarEditar) btnGuardarEditar.classList.remove('hidden');

        document.getElementById('editar-tarea-id').value = evento.extendedProps.tarea_id || '';
        document.getElementById('editar-tarea-titulo').value = evento.title.replace('📌 ', '');

        let fechaObj = evento.start;
        if (fechaObj) {
            let year = fechaObj.getFullYear();
            let month = String(fechaObj.getMonth() + 1).padStart(2, '0');
            let day = String(fechaObj.getDate()).padStart(2, '0');
            document.getElementById('editar-tarea-fecha').value = `${year}-${month}-${day}`;
        }
    } else {
        // Modo Beca
        let catText = (evento.extendedProps && evento.extendedProps.categoria) ? evento.extendedProps.categoria : '🎓 Convocatoria de Beca';
        document.getElementById('modal-categoria').innerHTML = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">${catText}</span>`;
        document.getElementById('modal-titulo').innerText = evento.title.replace('🎓 ', '');
        document.getElementById('modal-descripcion').innerText = (evento.extendedProps && evento.extendedProps.descripcion) ? evento.extendedProps.descripcion : 'Sin información disponible.';

        if (contenedorBeca) contenedorBeca.classList.remove('hidden');
        if (formEditarTarea) formEditarTarea.classList.add('hidden');
        if (btnEliminar) btnEliminar.classList.add('hidden');
        if (btnGuardarEditar) btnGuardarEditar.classList.add('hidden');

        let checklistContainer = document.getElementById('modal-checklist');
        if (checklistContainer) {
            checklistContainer.innerHTML = '';
            let checklist = (evento.extendedProps && evento.extendedProps.checklist) ? evento.extendedProps.checklist : [];

            if (checklist.length === 0) {
                checklistContainer.innerHTML = '<p class="text-xs text-slate-400 italic bg-slate-50 p-3 rounded-lg border border-slate-100 text-center">No hay requisitos en la lista de verificación para esta convocatoria.</p>';
            } else {
                checklist.forEach(item => {
                    checklistContainer.innerHTML += `
                        <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition">
                            <input type="checkbox" ${item.completado ? 'checked' : ''} class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700 ${item.completado ? 'line-through text-slate-400' : ''}">${item.texto}</span>
                        </label>
                    `;
                });
            }
        }
    }

    modalBeca.classList.remove('hidden');
};

window.cerrarModal = function () {
    let modal = document.getElementById('modal-beca');
    if (modal) modal.classList.add('hidden');
};

window.cerrarModalCrear = function () {
    let modal = document.getElementById('modal-crear-tarea');
    if (modal) modal.classList.add('hidden');
};

// Toast notification helper
function mostrarToast(mensaje, tipo = 'info') {
    let toast = document.createElement('div');
    toast.className = `fixed bottom-5 right-5 z-50 px-5 py-3 rounded-xl shadow-xl text-sm font-semibold flex items-center gap-2 transform transition-all duration-300 translate-y-5 opacity-0 ${
        tipo === 'success' ? 'bg-emerald-600 text-white' :
        tipo === 'error' ? 'bg-rose-600 text-white' :
        tipo === 'warning' ? 'bg-amber-500 text-white' : 'bg-slate-800 text-white'
    }`;
    toast.innerHTML = mensaje;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-5', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-5');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
