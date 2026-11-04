<?php
require_once '../config/database.php';
require_once '../models/cita.php';

$titulo_pagina = 'Citas - Consultorio Medico';
$pagina_activa = 'citas';
$cargar_fullcalendar = true;

// Conectar a la base de datos 
$database = new Database();
$db = $database->getConnection();
$citaModel = new cita($db);

// Obtener lista de médicos 
$medicos_bd = $citaModel->obtenerMedicos();
$medicos = [];
foreach ($medicos_bd as $m) {
    $medicos[] = [
        'id_medico'       => $m['id_medico'],
        'nombre_completo' => 'Dr. ' . $m['nombre'] . ' ' . $m['apellido_paterno'],
        'especialidad'    => $m['especialidad']
    ];
}

// Obtener lista de pacientes para el modal de Nueva Cita
$pacientes_recientes = $citaModel->obtenerPacientes();

// 4. Obtener las citas 
$citas_bd = $citaModel->obtenerCitas();
$eventos_citas = [];

foreach ($citas_bd as $c) {
    $timestamp_inicio = strtotime($c['fecha']);
    $timestamp_fin    = $timestamp_inicio + 3600; 

    $eventos_citas[] = [
        'id'    => (int)$c['id_cita'],
        'title' => $c['paciente_nombre'] . ' ' . $c['paciente_apellido'],
        'start' => date('Y-m-d\TH:i:s', $timestamp_inicio),
        'end'   => date('Y-m-d\TH:i:s', $timestamp_fin),
        'extendedProps' => [
            'id_paciente' => (int)$c['id_paciente'],
            'id_medico'   => (int)$c['id_medico'],
            'medico'      => 'Dr. ' . $c['medico_nombre'] . ' ' . $c['medico_apellido'],
            'estado'      => $c['estado']
        ]
    ];
}

include_once '../templatesReutilizables/header.php';
include_once '../templatesReutilizables/sidebar.php';
?>

<main class="col-md-9 col-lg-10 p-4" style="background-color: #e6f0fe;">

    <!-- SECCIÓN PRINCIPAL DE CITAS -->
    <section class="bg-white p-4 rounded-3 shadow-sm mb-4">
        <header class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Citas</h4>
            <button type="button" class="btn btn-primary fw-semibold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalCita">
                <i class="bi bi-plus-lg me-1"></i>Nueva cita
            </button>
        </header>

        <!-- Barra de Filtros y Selector  -->
        <form class="row g-2 align-items-center mb-3" id="formFiltrosCitas" onsubmit="return false;">
            <!-- Selector Vista Lista / Calendario -->
            <div class="col-auto">
                <div class="btn-group btn-group-sm" role="group" aria-label="Modo de visualización">
                    <button type="button" id="btnModoLista" class="btn btn-primary active">
                        <i class="bi bi-list-ul me-1"></i>Lista
                    </button>
                    <button type="button" id="btnModoCalendario" class="btn btn-outline-primary">
                        <i class="bi bi-calendar3 me-1"></i>Calendario
                    </button>
                </div>
            </div>

            <!-- Filtro: Doctor -->
            <div class="col-sm-6 col-md-auto">
                <select id="filtroMedico" class="form-select form-select-sm">
                    <option value="">Todos los doctores</option>
                    <?php foreach ($medicos as $m): ?>
                        <option value="<?= (int)$m['id_medico'] ?>"><?= htmlspecialchars($m['nombre_completo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtro: Estado -->
            <div class="col-sm-6 col-md-auto">
                <select id="filtroEstado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="programada">Programada</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="concluida">Concluida</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>

            <!-- Filtro: Fecha Desde / Hasta -->
            <div class="col-6 col-md-auto">
                <input type="date" id="filtroFechaInicio" class="form-control form-control-sm" aria-label="Fecha desde">
            </div>
            <div class="col-6 col-md-auto">
                <input type="date" id="filtroFechaFin" class="form-control form-control-sm" aria-label="Fecha hasta">
            </div>

            <!-- Filtro: Buscar Paciente -->
            <div class="col-12 col-md">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="search" id="filtroPaciente" class="form-control border-start-0" placeholder="Buscar paciente...">
                </div>
            </div>
        </form>

        <!--Navegación  y Selector de Fechas (Solo vista Calendario)-->
        <nav id="navControlesCalendario" class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pt-2 border-top d-none" aria-label="Controles del calendario">
            <div class="d-flex align-items-center gap-2">
                <button type="button" id="btnPrevWeek" class="btn btn-outline-primary btn-sm rounded-3" aria-label="Anterior">&lt;</button>
                <time id="calendarTitle" class="fw-bold fs-6 text-capitalize"></time>
                <button type="button" id="btnNextWeek" class="btn btn-outline-primary btn-sm rounded-3" aria-label="Siguiente">&gt;</button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" id="btnToday" class="btn btn-outline-secondary btn-sm rounded-3">Hoy</button>
                <div class="btn-group btn-group-sm" role="group" aria-label="Vistas de calendario">
                    <button type="button" class="btn btn-outline-primary btn-vista-cal" data-view="timeGridDay">Día</button>
                    <button type="button" class="btn btn-primary btn-vista-cal active" data-view="timeGridWeek">Semana</button>
                    <button type="button" class="btn btn-outline-primary btn-vista-cal" data-view="dayGridMonth">Mes</button>
                </div>
            </div>
        </nav>

        <!-- Contenedor de "Calendario" -->
        <div id="calendar" class="d-none" data-eventos='<?= htmlspecialchars(json_encode($eventos_citas, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'></div>

        <!-- Contenedor de "Lista"-->
        <div id="vistaListaCitas" class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th scope="col" class="ps-3">ID</th>
                        <th scope="col">Paciente</th>
                        <th scope="col">Médico</th>
                        <th scope="col">Fecha y Hora</th>
                        <th scope="col">Estado</th>
                        <th scope="col" class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaListaCitasBody">
                    <?php foreach ($eventos_citas as $ev): 
                        $est = $ev['extendedProps']['estado'];
                        $badgeMap = [
                            'concluida'  => 'bg-success-subtle text-success-emphasis',
                            'confirmada' => 'bg-primary-subtle text-primary-emphasis',
                            'programada' => 'bg-warning-subtle text-warning-emphasis',
                            'cancelada'  => 'bg-danger-subtle text-danger-emphasis'
                        ];
                        $badgeClase = $badgeMap[$est] ?? 'bg-secondary-subtle';
                    ?>
                        <tr>
                            <td class="ps-3 fw-medium text-secondary">#<?= (int)$ev['id'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($ev['title']) ?></td>
                            <td class="text-secondary small"><?= htmlspecialchars($ev['extendedProps']['medico']) ?></td>
                            <td><time><?= date('d/m/Y H:i', strtotime($ev['start'])) ?></time></td>
                            <td><span class="badge <?= $badgeClase ?>"><?= ucfirst(htmlspecialchars($est)) ?></span></td>
                            <td class="text-end pe-3">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-3 me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCita"
                                    data-id="<?= (int)$ev['id'] ?>"
                                    data-paciente="<?= (int)$ev['extendedProps']['id_paciente'] ?>"
                                    data-fecha="<?= htmlspecialchars(substr($ev['start'], 0, 16)) ?>"
                                    data-estado="<?= htmlspecialchars($est) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarCita"
                                    data-id="<?= (int)$ev['id'] ?>"
                                    data-paciente="<?= htmlspecialchars($ev['title']) ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- MODAL CREAR / EDITAR CITA  -->
    <div class="modal fade" id="modalCita" tabindex="-1" aria-labelledby="modalCitaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow" action="../controllers/citaController.php" method="POST">
                <header class="modal-header bg-primary-subtle border-0">
                    <h5 class="modal-title fw-bold text-primary-emphasis" id="modalCitaLabel">
                        <i class="bi bi-calendar-plus me-2"></i>Agendar / Editar Cita
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4">
                    <!-- Campo oculto para edición (id_cita) -->
                    <input type="hidden" name="id_cita" id="id_cita" value="">

                    <!-- Selección de Paciente -->
                    <div class="mb-3">
                        <label for="id_paciente" class="form-label fw-semibold small">Paciente</label>
                        <select name="id_paciente" id="id_paciente" class="form-select" required>
                            <option value="" selected disabled>Selecciona un paciente...</option>
                            <?php foreach ($pacientes_recientes as $p): ?>
                                <option value="<?= (int)$p['id_paciente'] ?>">
                                    <?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido_paterno']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="mb-3">
                        <label for="fecha" class="form-label fw-semibold small">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha" id="fecha" class="form-control" required>
                    </div>

                    <!-- Estado de la cita -->
                    <div class="mb-2">
                        <label for="estado" class="form-label fw-semibold small">Estado de la cita</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="programada">Programada</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="concluida">Concluida</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">Guardar Cita</button>
                </footer>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRMAR ELIMINACIÓN DE CITA -->
    <div class="modal fade" id="modalEliminarCita" tabindex="-1" aria-labelledby="modalEliminarCitaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <header class="modal-header bg-danger-subtle border-0">
                    <h5 class="modal-title fw-bold text-danger-emphasis" id="modalEliminarCitaLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Cita
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-trash3 fs-1"></i>
                    </div>
                    <p class="mb-1 fs-6">¿Estás seguro de que deseas eliminar la cita del paciente:</p>
                    <p class="fw-bold fs-5 text-dark mb-0" id="nombrePacienteEliminar"></p>
                    <small class="text-muted">Esta acción no se puede deshacer.</small>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="btnConfirmarEliminar" class="btn btn-danger rounded-3 px-4 fw-semibold">
                        <i class="bi bi-trash me-1"></i>Sí, eliminar
                    </a>
                </footer>
            </div>
        </div>
    </div>

</main>

<?php
$script_adicional = 'js/calendario.js';
include_once '../templatesReutilizables/footer.php';
?>