<?php
require_once '../config/database.php';
require_once '../models/reporte.php';

$titulo_pagina = 'Reportes y Estadísticas - Consultorio Médico';
$pagina_activa = 'reportes';

//  Conectar a la base de datos e instanciar el modelo Reporte
$database = new Database();
$db = $database->getConnection();
$reporteModel = new reporte($db);

// Obtener métricas en vivo desde la base de datos cexp_plus
$total_pacientes  = $reporteModel->contarPacientes();
$citas_pendientes = $reporteModel->contarCitasPendientes();
$total_consultas  = $reporteModel->contarConsultas();
$tasa_asistencia  = $reporteModel->calcularTasaAsistencia();
$proximas_citas   = $reporteModel->obtenerProximasCitas(4);

// Arreglo auxiliar para mostrar el día de la semana en español
$dias_semana = [
    'Sunday'    => 'Domingo',
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes',
    'Saturday'  => 'Sábado'
];

include_once '../templatesReutilizables/header.php';
include_once '../templatesReutilizables/sidebar.php';
?>

<main class="col-md-9 col-lg-10 p-4" style="background-color: #f4f7fe;">

    <!-- BARRA SUPERIOR: TÍTULO, BUSCADOR Y BOTÓN NUEVA CITA -->
    <header class="bg-white p-3 px-4 rounded-4 shadow-sm mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-graph-up text-primary fs-5"></i>
            <h5 class="fw-bold mb-0 text-dark">REPORTES &amp; ESTADÍSTICAS</h5>
            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis fw-semibold px-3 py-2 ms-2">
                Semana Actual
            </span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <form action="citas.php" method="GET" class="m-0">
                <div class="input-group input-group-sm" style="min-width: 230px;">
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                        <i class="bi bi-search text-secondary"></i>
                    </span>
                    <input type="search" name="buscar" id="buscadorDashboard" class="form-control bg-light border-0 rounded-end-pill py-2" placeholder="Buscar paciente o ID...">
                </div>
            </form>
            <a href="citas.php" class="btn btn-primary btn-sm rounded-pill fw-semibold px-3 py-2 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i>Nueva Cita
            </a>
        </div>
    </header>

    <!-- FILA DE 4 TARJETAS DE INDICADORES (KPIs) -->
    <section class="row g-3 mb-4">
        <!-- Tarjeta 1: Pacientes Activos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="bg-white p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-secondary fw-medium small">Pacientes Activos</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 38px; height: 38px;">
                        <i class="bi bi-people-fill fs-5"></i>
                    </span>
                </div>
                <h2 class="fw-bold text-dark mb-2"><?= $total_pacientes ?></h2>
                <small class="text-success fw-semibold">
                    <i class="bi bi-graph-up-arrow me-1"></i>+8.4% este mes
                </small>
            </article>
        </div>

        <!-- Tarjeta 2: Citas Pendientes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="bg-white p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-secondary fw-medium small">Citas Pendientes</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis" style="width: 38px; height: 38px;">
                        <i class="bi bi-clock-fill fs-5"></i>
                    </span>
                </div>
                <h2 class="fw-bold text-dark mb-2"><?= $citas_pendientes ?></h2>
                <small class="text-warning-emphasis fw-semibold">
                    <i class="bi bi-calendar-event me-1"></i>Próximas 48 horas
                </small>
            </article>
        </div>

        <!-- Tarjeta 3: Consultas este Mes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="bg-white p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-secondary fw-medium small">Consultas este Mes</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center bg-info-subtle text-info-emphasis" style="width: 38px; height: 38px;">
                        <i class="bi bi-heart-pulse-fill fs-5"></i>
                    </span>
                </div>
                <h2 class="fw-bold text-dark mb-2"><?= $total_consultas ?></h2>
                <small class="text-success fw-semibold">
                    <i class="bi bi-arrow-up me-1"></i>+12% vs mes anterior
                </small>
            </article>
        </div>

        <!-- Tarjeta 4: Tasa de Asistencia -->
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="bg-white p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-secondary fw-medium small">Tasa de Asistencia</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 38px; height: 38px;">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </span>
                </div>
                <h2 class="fw-bold text-dark mb-2"><?= $tasa_asistencia ?>%</h2>
                <small class="text-success fw-semibold">
                    <i class="bi bi-check2 me-1"></i>Excelente cumplimiento
                </small>
            </article>
        </div>
    </section>

    <!-- FILA INFERIOR: GRÁFICA DE TENDENCIA Y PRÓXIMAS CITAS -->
    <section class="row g-4">
        <!-- Columna Izquierda: Gráfica de Tendencia -->
        <div class="col-12 col-lg-8">
            <article class="bg-white p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <header class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">
                            <i class="bi bi-graph-up text-primary me-1"></i>Tendencia de Consultas y Nuevos Pacientes
                        </h6>
                        <small class="text-secondary">Comparativa mensual acumulada</small>
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-bold">2026</span>
                </header>

                <!-- Gráfico SVG fiel al diseño -->
                <div class="my-2">
                    <svg viewBox="0 0 650 240" class="w-100" style="overflow: visible;">
                        <defs>
                            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#4e73df" stop-opacity="0.16" />
                                <stop offset="100%" stop-color="#4e73df" stop-opacity="0.02" />
                            </linearGradient>
                        </defs>

                        <!-- Líneas horizontales guía -->
                        <line x1="20" y1="35"  x2="630" y2="35"  stroke="#f1f4f9" stroke-width="1.5" />
                        <line x1="20" y1="85"  x2="630" y2="85"  stroke="#f1f4f9" stroke-width="1.5" />
                        <line x1="20" y1="135" x2="630" y2="135" stroke="#f1f4f9" stroke-width="1.5" />
                        <line x1="20" y1="185" x2="630" y2="185" stroke="#f1f4f9" stroke-width="1.5" />

                        <!-- Área sombreada bajo la curva principal -->
                        <polygon points="20,145 135,112 250,132 365,68 480,92 595,40 630,28 630,185 20,185" fill="url(#areaGrad)" />

                        <!-- Línea punteada: Nuevos Pacientes -->
                        <polyline fill="none" stroke="#a6c1ee" stroke-width="3" stroke-dasharray="4,4"
                                  points="20,175 135,152 250,158 365,120 480,126 595,85 630,74" />

                        <!-- Línea sólida: Consultas Realizadas -->
                        <polyline fill="none" stroke="#4e73df" stroke-width="3.5"
                                  points="20,145 135,112 250,132 365,68 480,92 595,40 630,28" />

                        <!-- Puntos destacados -->
                        <circle cx="365" cy="68" r="5.5" fill="#4e73df" stroke="#ffffff" stroke-width="2" />
                        <circle cx="595" cy="40" r="5.5" fill="#4e73df" stroke="#ffffff" stroke-width="2" />

                        <!-- Etiquetas de Meses -->
                        <text x="35"  y="218" fill="#6c757d" font-size="12" font-weight="500">Mayo</text>
                        <text x="145" y="218" fill="#6c757d" font-size="12" font-weight="500">Junio</text>
                        <text x="260" y="218" fill="#6c757d" font-size="12" font-weight="500">Julio</text>
                        <text x="370" y="218" fill="#6c757d" font-size="12" font-weight="500">Agosto</text>
                        <text x="475" y="218" fill="#6c757d" font-size="12" font-weight="500">Septiembre</text>
                        <text x="585" y="218" fill="#6c757d" font-size="12" font-weight="500">Octubre</text>
                    </svg>
                </div>

                <!-- Leyenda inferior -->
                <footer class="d-flex justify-content-center align-items-center gap-4 pt-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background-color: #4e73df;"></span>
                        <small class="text-secondary fw-semibold">Consultas Realizadas</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background-color: #a6c1ee;"></span>
                        <small class="text-secondary fw-semibold">Nuevos Pacientes</small>
                    </div>
                </footer>
            </article>
        </div>

        <!-- Columna Derecha: Próximas Citas -->
        <div class="col-12 col-lg-4">
            <article class="bg-white p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <header class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-list-check text-warning me-2"></i>Próximas Citas
                        </h6>
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-1 fw-semibold">
                            <?= $citas_pendientes ?> Pendientes
                        </span>
                    </header>

                    <!-- Lista de tarjetas de próximas citas -->
                    <div class="d-flex flex-column gap-3" id="listaProximasCitas">
                        <?php if (empty($proximas_citas)): ?>
                            <div class="p-3 rounded-3 bg-light text-center text-secondary small">
                                No hay citas pendientes próximas.
                            </div>
                        <?php else: ?>
                            <?php foreach ($proximas_citas as $cita):
                                $ts = strtotime($cita['fecha']);
                                $dia_ing = date('l', $ts);
                                $dia_esp = $dias_semana[$dia_ing] ?? $dia_ing;
                                $hora_fmt = date('h:i A', $ts);
                                $id_fmt = str_pad($cita['id_cita'], 3, '0', STR_PAD_LEFT);
                                $nombre_completo = $cita['paciente_nombre'] . ' ' . $cita['paciente_apellido'];

                                // Etiqueta según el estado
                                if ($cita['estado'] === 'confirmada') {
                                    $badge_clase = 'bg-info-subtle text-info-emphasis';
                                    $badge_texto = 'Confirmada';
                                } else {
                                    $badge_clase = 'bg-warning-subtle text-warning-emphasis';
                                    $badge_texto = 'Pendiente';
                                }
                            ?>
                                <div class="p-3 rounded-3 d-flex justify-content-between align-items-center item-proxima-cita" style="background-color: #f4f6fb;">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.92rem;">
                                            <?= htmlspecialchars($nombre_completo) ?>
                                        </h6>
                                        <small class="text-secondary" style="font-size: 0.78rem;">
                                            <?= $dia_esp ?>, <?= $hora_fmt ?> &bull; ID <?= $id_fmt ?>
                                        </small>
                                    </div>
                                    <span class="badge <?= $badge_clase ?> rounded-2 px-2 py-1 fw-semibold">
                                        <?= $badge_texto ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Botón inferior Ir al Calendario Completo -->
                <div class="mt-4 pt-2">
                    <a href="citas.php" class="btn btn-light w-100 rounded-3 py-2 fw-bold text-dark border-0" style="background-color: #f4f6fb;">
                        Ir al Calendario Completo <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </article>
        </div>
    </section>

</main>

<?php
include_once '../templatesReutilizables/footer.php';
?>
