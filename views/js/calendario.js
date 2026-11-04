document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const titleEl = document.getElementById('calendarTitle');
    const todosLosEventos = JSON.parse(calendarEl.dataset.eventos || '[]');

    const badgeClases = {
        concluida: 'bg-success-subtle text-success-emphasis',
        completada: 'bg-success-subtle text-success-emphasis',
        confirmada: 'bg-primary-subtle text-primary-emphasis',
        programada: 'bg-warning-subtle text-warning-emphasis',
        cancelada: 'bg-danger-subtle text-danger-emphasis'
    };

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        initialDate: '2026-10-12',
        locale: 'es',
        firstDay: 1,
        hiddenDays: [0, 6],
        slotMinTime: '08:00:00',
        slotMaxTime: '18:00:00',
        slotDuration: '01:00:00',
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        allDaySlot: false,
        headerToolbar: false,
        height: 'calc(100vh - 230px)',
        expandRows: true,
        dayHeaderFormat: { weekday: 'long' },
        events: todosLosEventos,

        datesSet: function (info) {
            if (titleEl) {
                titleEl.textContent = info.view.title;
            }
        },

        eventContent: function (arg) {
            const props = arg.event.extendedProps || {};
            const estado = props.estado || 'programada';
            const medico = props.medico || '';
            const estadoTexto = estado.charAt(0).toUpperCase() + estado.slice(1);
            const claseBadge = badgeClases[estado] || 'bg-secondary-subtle text-secondary-emphasis';

            const contenedor = document.createElement('article');
            contenedor.className = 'card bg-primary-subtle border-0 rounded-3 p-2 w-100 h-100 d-flex flex-column justify-content-between overflow-hidden';

            
            const esVistaDia = arg.view.type === 'timeGridDay';

            contenedor.innerHTML = `
                <header class="d-flex justify-content-between align-items-start gap-1">
                    <div class="text-truncate">
                        <time class="small fw-bold text-primary-emphasis">${arg.timeText}</time>
                        <span class="small fw-semibold text-dark">${esVistaDia ? '— ' + arg.event.title : ''}</span>
                    </div>
                    <menu class="d-flex gap-1 m-0 p-0 flex-shrink-0">
                        <button type="button" class="btn btn-sm p-0 border-0 text-primary" title="Editar cita"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCita"
                            data-id="${arg.event.id}"
                            data-paciente="${props.id_paciente}"
                            data-fecha="${arg.event.startStr.substring(0, 16)}"
                            data-estado="${estado}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm p-0 border-0 text-danger"
                            title="Eliminar cita"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminarCita"
                            data-id="${arg.event.id}"
                            data-paciente="${arg.event.title}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </menu>
                </header>
                ${!esVistaDia ? `<p class="small text-dark fw-medium text-truncate mb-1">${arg.event.title}</p>` : `<p class="small text-secondary mb-1">${medico}</p>`}
                <div class="d-flex flex-wrap align-items-center gap-1 mt-auto">
                    <span class="badge ${claseBadge}">${estadoTexto}</span>
                    ${esVistaDia ? `
                        <button type="button" class="badge bg-white text-dark border border-secondary-subtle fw-normal"><i class="bi bi-check-circle me-1 text-success"></i>Confirmar</button>
                        <button type="button" class="badge bg-white text-danger border border-danger-subtle fw-normal"><i class="bi bi-x-circle me-1"></i>Cancelar cita</button>
                    ` : ''}
                </div>
            `;
            return { domNodes: [contenedor] };
        }
    });

    calendar.render();

    // Navegación Anterior / Siguiente / Hoy
    const btnPrev = document.getElementById('btnPrevWeek');
    const btnNext = document.getElementById('btnNextWeek');
    const btnToday = document.getElementById('btnToday');

    if (btnPrev) btnPrev.addEventListener('click', () => calendar.prev());
    if (btnNext) btnNext.addEventListener('click', () => calendar.next());
    if (btnToday) btnToday.addEventListener('click', () => calendar.today());

    // Cambio de vista: Día | Semana | Mes
    const botonesVista = document.querySelectorAll('.btn-vista-cal');
    botonesVista.forEach(btn => {
        btn.addEventListener('click', function () {
            botonesVista.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary', 'active');
            calendar.changeView(this.dataset.view);
        });
    });

    // Alternar entre Modo Calendario y Modo Lista
    const btnModoLista = document.getElementById('btnModoLista');
    const btnModoCalendario = document.getElementById('btnModoCalendario');
    const vistaListaEl = document.getElementById('vistaListaCitas');
    const navControlesCalendario = document.getElementById('navControlesCalendario');

    if (btnModoLista && btnModoCalendario && vistaListaEl) {
        btnModoLista.addEventListener('click', function () {
            btnModoLista.classList.replace('btn-outline-primary', 'btn-primary');
            btnModoLista.classList.add('active');
            btnModoCalendario.classList.replace('btn-primary', 'btn-outline-primary');
            btnModoCalendario.classList.remove('active');

            calendarEl.classList.add('d-none');
            if (navControlesCalendario) navControlesCalendario.classList.add('d-none');
            vistaListaEl.classList.remove('d-none');
        });

        btnModoCalendario.addEventListener('click', function () {
            btnModoCalendario.classList.replace('btn-outline-primary', 'btn-primary');
            btnModoCalendario.classList.add('active');
            btnModoLista.classList.replace('btn-primary', 'btn-outline-primary');
            btnModoLista.classList.remove('active');

            vistaListaEl.classList.add('d-none');
            calendarEl.classList.remove('d-none');
            if (navControlesCalendario) navControlesCalendario.classList.remove('d-none');
            calendar.updateSize();
        });
    }

    // Filtros de busqueda 
    const filtroMedico = document.getElementById('filtroMedico');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroFechaInicio = document.getElementById('filtroFechaInicio');
    const filtroFechaFin = document.getElementById('filtroFechaFin');
    const filtroPaciente = document.getElementById('filtroPaciente');

    function aplicarFiltros() {
        const medicoVal = filtroMedico ? filtroMedico.value : '';
        const estadoVal = filtroEstado ? filtroEstado.value : '';
        const inicioVal = filtroFechaInicio ? filtroFechaInicio.value : '';
        const finVal = filtroFechaFin ? filtroFechaFin.value : '';
        const textoVal = filtroPaciente ? filtroPaciente.value.toLowerCase().trim() : '';

        const filtrados = todosLosEventos.filter(ev => {
            const props = ev.extendedProps || {};
            const fechaEv = ev.start.substring(0, 10);

            if (medicoVal && String(props.id_medico) !== medicoVal) return false;
            if (estadoVal && props.estado !== estadoVal) return false;
            if (inicioVal && fechaEv < inicioVal) return false;
            if (finVal && fechaEv > finVal) return false;
            if (textoVal && !ev.title.toLowerCase().includes(textoVal)) return false;

            return true;
        });

        calendar.removeAllEvents();
        calendar.addEventSource(filtrados);
    }

    [filtroMedico, filtroEstado, filtroFechaInicio, filtroFechaFin].forEach(el => {
        if (el) el.addEventListener('change', aplicarFiltros);
    });
    if (filtroPaciente) filtroPaciente.addEventListener('input', aplicarFiltros);

    // Rellenar o limpiar el Modal al abrirse (Crear vs Editar)
    const modalCitaEl = document.getElementById('modalCita');
    if (modalCitaEl) {
        modalCitaEl.addEventListener('show.bs.modal', function (event) {
            const boton = event.relatedTarget;
            const inputIdCita = document.getElementById('id_cita');
            const selectPaciente = document.getElementById('id_paciente');
            const inputFecha = document.getElementById('fecha');
            const selectEstado = document.getElementById('estado');

            if (boton && boton.dataset.id) {
                // Modo Editar: Rellenar campos con los datos de la cita seleccionada
                inputIdCita.value = boton.dataset.id;
                selectPaciente.value = boton.dataset.paciente;
                inputFecha.value = boton.dataset.fecha;
                selectEstado.value = boton.dataset.estado;
            } else {
                // Modo Crear (+ Nueva cita): Limpiar campos
                inputIdCita.value = '';
                selectPaciente.value = '';
                inputFecha.value = '';
                selectEstado.value = 'programada';
            }
        });
    }

    // Rellenar el Modal de Confirmación de Eliminación al abrirse
    const modalEliminarEl = document.getElementById('modalEliminarCita');
    if (modalEliminarEl) {
        modalEliminarEl.addEventListener('show.bs.modal', function (event) {
            const boton = event.relatedTarget;
            const nombrePacienteEl = document.getElementById('nombrePacienteEliminar');
            const btnConfirmarEl = document.getElementById('btnConfirmarEliminar');

            if (boton && boton.dataset.id) {
                if (nombrePacienteEl) {
                    nombrePacienteEl.textContent = boton.dataset.paciente || '';
                }
                if (btnConfirmarEl) {
                    btnConfirmarEl.href = '../controllers/citaController.php?accion=eliminar&id=' + boton.dataset.id;
                }
            }
        });
    }
});
