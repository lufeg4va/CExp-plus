<?php

$pagina_activa = $pagina_activa ?? '';
?>

<aside class="col-md-3 col-lg-2 p-3 bg-white d-flex flex-column border-end shadow-sm" id="sidebar">

    <!-- Perfil del Médico -->
    <div class="profile-card d-flex align-items-center gap-2 p-2 mb-4 border rounded-3 bg-light">
        <div class="doctor-avatar bg-primary-subtle rounded-5 d-flex align-items-center justify-content-center">
            <i class="bi bi-person text-primary fs-5"></i>
        </div>

        <div class="doctor-info text-start">
            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">Dra. Samantha Samudio</h6>
            <small class="text-muted" style="font-size: 0.75rem;">Médico General</small>
        </div>
    </div>

    <!-- Menú de Navegación -->
    <h6 class="text-muted fw-bold mb-3">MENÚ PRINCIPAL</h6>

    <nav class="nav flex-column mb-auto gap-2">
        <!-- Consultas -->
        <a href='consultas.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'consultas') ? 'active' : '' ?>'>
            <i class="bi bi-heart-pulse me-2"></i>
            <span class='fw-bold'>Consultas</span>
        </a>

        <!-- Citas -->
        <a href='citas.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'citas') ? 'active' : '' ?>'>
            <i class='bi bi-calendar-check me-2'></i>
            <span class='fw-bold'>Citas</span>
        </a>

        <!-- Pacientes -->
        <a href='pacientes.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'pacientes') ? 'active' : '' ?>'>
            <i class="bi bi-person-fill me-2"></i>
            <span class='fw-bold'>Pacientes</span>
        </a>

        <!-- Historial -->
        <a href='historial.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'historial') ? 'active' : '' ?>'>
            <i class="bi bi-clock-history me-2"></i>
            <span class='fw-bold'>Historial</span>
        </a>

        <!-- Agenda -->
        <a href='agenda.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'agenda') ? 'active' : '' ?>'>
            <i class="bi bi-journal-text me-2"></i>
            <span class='fw-bold'>Agenda</span>
        </a>

        <!-- Reportes -->
        <a href='reportes.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'reportes') ? 'active' : '' ?>'>
            <i class="bi bi-bar-chart-line me-2"></i>
            <span class='fw-bold'>Reportes</span>
        </a>

        <!-- Configuración -->
        <a href='configuracion.php' class='nav-link sidebar-item d-flex align-items-center p-3 <?= ($pagina_activa === 'configuracion') ? 'active' : '' ?>'>
            <i class="bi bi-gear me-2"></i>
            <span class='fw-bold'>Configuracion</span>
        </a>
    </nav>

    <!-- Botón Cerrar sesión -->
    <div class="mt-auto pt-3">
        <a href="../controllers/logoutController.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right m-1"></i>Cerrar sesión</a>
    </div>
</aside>

