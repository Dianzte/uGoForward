<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendario de Becas y Agenda</title>

    <!-- Tailwind CSS  -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FullCalendar CSS y JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans">

    <!-- Layout Principal -->
    <div class="max-w-7xl mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- BARRA LATERAL: Filtros y Mis Tareas Rápidas -->
        <aside class="lg:col-span-1 space-y-6">
            
            <!-- Leyenda del Semáforo -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-700 mb-3 text-sm tracking-wide uppercase">Estado de Convocatorias</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-slate-600">Más de 15 días (+15d)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span class="text-slate-600">Próximo a cerrar (<=15d)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                        <span class="text-slate-600">Últimos días (<=5d)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                        <span class="text-slate-600">Tarea de Agenda Personal</span>
                    </div>
                </div>
            </div>

            <!-- Filtros Rápidos -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-700 mb-3 text-sm tracking-wide uppercase">Filtros</h3>
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-slate-500">Nivel de Estudio</label>
                    <select id="filtro-nivel" class="w-full text-sm border-slate-300 rounded-lg p-2 border focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">Todos los niveles</option>
                        <option value="grado">Pregrado / Licenciatura</option>
                        <option value="maestria">Maestría / Posgrado</option>
                        <option value="doctorado">Doctorado</option>
                    </select>

                    <button id="btn-nueva-tarea" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                        + Agregar Tarea a Agenda
                    </button>
                </div>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL: Calendario -->
        <main class="lg:col-span-3 bg-white p-4 md:p-6 rounded-xl shadow-sm border border-slate-200">
            <div id="calendar"></div>
        </main>

    </div>

    <!-- MODAL: Detalle de Beca / Checklist -->
    <div id="modal-beca" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
            
            <!-- Cabecera del Modal -->
            <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-start">
                <div>
                    <span id="modal-categoria" class="text-xs font-bold uppercase tracking-wider text-indigo-600">Beca</span>
                    <h2 id="modal-titulo" class="text-xl font-bold text-slate-800 mt-1">Cargando...</h2>
                </div>
                <button onclick="cerrarModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Cuerpos del Modal / Checklist -->
            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                <div>
                    <h4 class="text-xs font-semibold text-slate-400 uppercase">Descripción</h4>
                    <p id="modal-descripcion" class="text-sm text-slate-600 mt-1">Sin información disponible.</p>
                </div>

                <div>
                    <h4 class="text-xs font-semibold text-slate-400 uppercase mb-2">Checklist de Postulación</h4>
                    <div id="modal-checklist" class="space-y-2">
                        <!-- Se llena dinámicamente desde JS -->
                    </div>
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-2">
                <button onclick="cerrarModal()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-200 rounded-lg transition">Cerrar</button>
            </div>
        </div>
    </div>

        <!-- MODAL: Detalle de Beca / Checklist --><!-- MODAL: Crear Nueva Tarea -->
    <div id="modal-crear-tarea" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            
            <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">📌 Nueva Tarea Personal</h3>
                <button onclick="cerrarModalCrear()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none">&times;</button>
            </div>

            <form id="form-crear-tarea" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Título de la Tarea</label>
                    <input type="text" id="tarea-titulo" required placeholder="Ej. Revisar ensayo de beca" class="w-full border-slate-300 rounded-lg p-2.5 border text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Fecha</label>
                    <input type="date" id="tarea-fecha" required class="w-full border-slate-300 rounded-lg p-2.5 border text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" onclick="cerrarModalCrear()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">Guardar Tarea</button>
                </div>
            </form>

        </div>
    </div>




    <!-- JAVASCRIPT INICIAL DE FULLCALENDAR -->
    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },

                
                events: '/api/becas-calendario/eventos',

                // Al hacer clic en un evento
                eventClick: function(info) {
                    abrirModal(info.event);
                }

            });

            calendar.render();
        });

        function abrirModal(evento) {
            document.getElementById('modal-titulo').innerText = evento.title;
            document.getElementById('modal-descripcion').innerText = evento.extendedProps.descripcion || 'Sin descripción';
            document.getElementById('modal-categoria').innerText = evento.extendedProps.categoria || 'General';


            // Renderizar el Checklist
            let checklistContainer = document.getElementById('modal-checklist');
            checklistContainer.innerHTML = '';

            let checklist = evento.extendedProps.checklist || [];
            if(checklist.length === 0) {
                checklistContainer.innerHTML = '<p class="text-xs text-slate-400 italic">No hay tareas asociadas.</p>';
            } else {
                checklist.forEach(item => {
                    checklistContainer.innerHTML += `
                        <label class="flex items-center space-x-3 p-2 rounded-lg border border-slate-100 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" ${item.completado ? 'checked' : ''} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700 ${item.completado ? 'line-through text-slate-400' : ''}">${item.texto}</span>
                        </label>
                    `;
                });
            }

            

            document.getElementById('modal-beca').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-beca').classList.add('hidden');
        }


        // Javascript para crear nueva tarea
              
              document.addEventListener('DOMContentLoaded', function() {
          // ... tu código de FullCalendar aquí ...

          // 1. Asignar el clic al botón "Agregar Tarea a Agenda"
          let btnAgregar = document.getElementById('btn-nueva-tarea');
          if (btnAgregar) {
              btnAgregar.addEventListener('click', function() {
                  // Establece la fecha de hoy por defecto en el input date
                  document.getElementById('tarea-fecha').value = new Date().toISOString().split('T')[0];
                  document.getElementById('modal-crear-tarea').classList.remove('hidden');
              });
          }

          // 2. Escuchar el envío del formulario de crear tarea
          let formTarea = document.getElementById('form-crear-tarea');
          if (formTarea) {
              formTarea.addEventListener('submit', function(e) {
                  e.preventDefault();

                  let titulo = document.getElementById('tarea-titulo').value;
                  let fecha = document.getElementById('tarea-fecha').value;

                  // Enviar petición POST a Laravel
                  fetch('/api/calendario/tareas', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      },
                      body: JSON.stringify({
                          titulo: titulo,
                          fecha: fecha
                      })
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          cerrarModalCrear();
                          formTarea.reset();
                          // Refrescar los eventos del calendario dinámicamente
                          calendar.refetchEvents();
                      } else {
                          alert('Ocurrió un error al guardar la tarea.');
                      }
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      alert('No se pudo guardar la tarea. Revisa la consola o la sesión.');
                  });
              });
          }
      });


      function cerrarModalCrear() {
          document.getElementById('modal-crear-tarea').classList.add('hidden');
      }
    </script>
</body>
</html>