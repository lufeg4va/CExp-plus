<?php
$titulo_pagina = 'Citas - Consultorio Medico';
$pagina_activa = 'citas';

include_once 'templates/header.php';
include_once 'templates/sidebar.php';
?>

<main class="col-md-9 col-lg-10 p-4" style="background-color: #e6f0fe;">

    <!-- CALENDARIO -->
    <section class="bg-white p-4 rounded-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary fw-semibold px-3 py-2 rounded-3">+ Nueva cita</button>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-primary btn-sm">&lt;</button>
                <span class="fw-bold">21 Sept - 25 Sept 2026</span>
                <button class="btn btn-outline-primary btn-sm">&gt;</button>
            </div>
        </div>
    </section>

    <!-- SECCIÓN PACIENTES RECIENTES -->
    <section class="bg-white p-4 rounded-3 shadow-sm">
        <h5 class="fw-bold mb-3">Pacientes recientes</h5>
        <!-- Tabla de pacientes -->
        <div class="table-responsive">
            <table class="table align-middle patients-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre</th>
                        <th>Visita</th>
                        <th class="text-end pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Paciente 1: Gael -->
                    <tr>
                        <td class="ps-4 fw-medium text-secondary">2121</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="fw-semibold text-dark">Gael</span>
                            </div>
                        </td>
                        <td class="fw-medium text-secondary">21/09</td>
                        <td class="text-end pe-4">
                            <a href="#" class="btn-history">Ver Historial</a>
                        </td>
                    </tr>

                    <!-- Paciente 2: Ximena -->
                    <tr>
                        <td class="ps-4 fw-medium text-secondary">1301</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="fw-semibold text-dark">Ximena</span>
                            </div>
                        </td>
                        <td class="fw-medium text-secondary">22/09</td>
                        <td class="text-end pe-4">
                            <a href="#" class="btn-history">Ver Historial</a>
                        </td>
                    </tr>

                    <!-- Paciente 3: Alejandro -->
                    <tr>
                        <td class="ps-4 fw-medium text-secondary">1915</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="fw-semibold text-dark">Alejandro</span>
                            </div>
                        </td>
                        <td class="fw-medium text-secondary">22/09</td>
                        <td class="text-end pe-4">
                            <a href="#" class="btn-history">Ver Historial</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</main>

<?php
include_once 'templates/footer.php';
?>

