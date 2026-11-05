<?php
$titulo_pagina = 'Configuracion - Consultorio Medico Maroca';
$pagina_activa = 'configuracion';


$roles_lista = [
    ['id_grupo' => 1, 'nombre' => 'Administrador', 'total_usuarios' => 1],
    ['id_grupo' => 2, 'nombre' => 'Médico', 'total_usuarios' => 2],
    ['id_grupo' => 3, 'nombre' => 'Recepcionista', 'total_usuarios' => 1],
];

$permisos_lista = [
    ['id_permiso' => 1, 'nombre' => 'Gestionar Citas (Crear, Editar, Cancelar)', 'codename' => 'gestionar_citas'],
    ['id_permiso' => 2, 'nombre' => 'Gestionar Pacientes y Antecedentes', 'codename' => 'gestionar_pacientes'],
    ['id_permiso' => 3, 'nombre' => 'Registrar Consultas y Recetas', 'codename' => 'registrar_consultas'],
    ['id_permiso' => 4, 'nombre' => 'Ver Reportes y Estadísticas', 'codename' => 'ver_reportes'],
    ['id_permiso' => 5, 'nombre' => 'Administrar Usuarios, Roles y Permisos', 'codename' => 'admin_usuarios'],
];

$usuarios_lista = [
    [
        'id_usuario'      => 1,
        'nombre_completo' => 'Dra. Samantha Samudio',
        'username'        => 'ssamudio',
        'email'           => 'samantha@cexplus.com',
        'estado'          => 'activo',
        'id_grupo'        => 1,
        'rol_nombre'      => 'Administrador',
        'permisos_ids'    => [1, 2, 3, 4, 5]
    ],
    [
        'id_usuario'      => 2,
        'nombre_completo' => 'Dr. Roberto Gómez',
        'username'        => 'rgomez',
        'email'           => 'rgomez@cexplus.com',
        'estado'          => 'activo',
        'id_grupo'        => 2,
        'rol_nombre'      => 'Médico',
        'permisos_ids'    => [1, 2, 3]
    ],
    [
        'id_usuario'      => 3,
        'nombre_completo' => 'Laura Martínez',
        'username'        => 'lmartinez',
        'email'           => 'recepcion@cexplus.com',
        'estado'          => 'inactivo',
        'id_grupo'        => 3,
        'rol_nombre'      => 'Recepcionista',
        'permisos_ids'    => [1, 2]
    ]
];

include_once '../templatesReutilizables/header.php';
include_once '../templatesReutilizables/sidebar.php';
?>

<main class="col-md-9 col-lg-10 p-4" style="background-color: #e6f0fe;">

    <section class="bg-white p-4 rounded-3 shadow-sm mb-4">
        <header class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Gestión de Usuarios, Perfiles y Permisos</h4>
                <p class="text-muted small mb-0">Administra las cuentas del sistema, sus roles asignados y permisos de acceso.</p>
            </div>
        </header>

        <!-- NAVEGACIÓN POR PESTAÑAS-->
        <ul class="nav nav-tabs mb-4" id="tabsConfiguracion" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="tab-usuarios-btn" data-bs-toggle="tab" data-bs-target="#tabUsuarios" type="button" role="tab" aria-controls="tabUsuarios" aria-selected="true">
                    <i class="bi bi-people me-1"></i>1. Usuarios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="tab-roles-btn" data-bs-toggle="tab" data-bs-target="#tabRoles" type="button" role="tab" aria-controls="tabRoles" aria-selected="false">
                    <i class="bi bi-shield-lock me-1"></i>2. Perfiles / Roles
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="tab-permisos-btn" data-bs-toggle="tab" data-bs-target="#tabPermisos" type="button" role="tab" aria-controls="tabPermisos" aria-selected="false">
                    <i class="bi bi-key me-1"></i>3. Permisos
                </button>
            </li>
        </ul>

        <!-- CONTENIDO DE LAS PESTAÑAS -->
        <div class="tab-content" id="tabsConfiguracionContent">

            <!--PESTAÑA GESTIÓN DE USUARIOS-->
            <div class="tab-pane fade show active" id="tabUsuarios" role="tabpanel" aria-labelledby="tab-usuarios-btn">

                <!-- Barra de filtros y boton de ,Nuevo Usuario -->
                <form class="row g-2 align-items-center mb-3" id="formFiltrosUsuarios" onsubmit="return false;">
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="search" id="filtroBuscarUsuario" class="form-control border-start-0" placeholder="Buscar por nombre, usuario o correo...">
                        </div>
                    </div>

                    <div class="col-6 col-md-auto">
                        <select id="filtroRolUsuario" class="form-select form-select-sm">
                            <option value="">Todos los roles</option>
                            <?php foreach ($roles_lista as $r): ?>
                                <option value="<?= htmlspecialchars($r['nombre']) ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-6 col-md-auto">
                        <select id="filtroEstadoUsuario" class="form-select form-select-sm">
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-auto ms-md-auto text-end">
                        <button type="button" class="btn btn-primary btn-sm fw-semibold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="btnNuevoUsuario">
                            <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
                        </button>
                    </div>
                </form>

                <!-- Tabla de Usuarios -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col" class="ps-3">ID</th>
                                <th scope="col">Nombre Completo</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Correo Electrónico</th>
                                <th scope="col">Perfil / Rol</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios_lista as $u):
                                $badgeEstado = ($u['estado'] === 'activo')
                                    ? 'bg-success-subtle text-success-emphasis'
                                    : 'bg-danger-subtle text-danger-emphasis';
                            ?>
                                <tr data-rol="<?= htmlspecialchars($u['rol_nombre']) ?>" data-estado="<?= htmlspecialchars($u['estado']) ?>">
                                    <td class="ps-3 fw-medium text-secondary">#<?= (int)$u['id_usuario'] ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($u['nombre_completo']) ?></td>
                                    <td><span class="badge bg-light text-dark border">@<?= htmlspecialchars($u['username']) ?></span></td>
                                    <td class="text-secondary small"><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary-emphasis">
                                            <?= htmlspecialchars($u['rol_nombre']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $badgeEstado ?>">
                                            <?= ucfirst(htmlspecialchars($u['estado'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <!-- Botón Asignar/Revocar Permisos -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 me-1 btn-permisos-usuario"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAsignarPermisos"
                                            data-id="<?= (int)$u['id_usuario'] ?>"
                                            data-nombre="<?= htmlspecialchars($u['nombre_completo']) ?>"
                                            data-permisos="<?= htmlspecialchars(json_encode($u['permisos_ids'])) ?>">
                                            <i class="bi bi-key"></i> Permisos
                                        </button>

                                        <!-- Botón Editar Usuario -->
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3 me-1 btn-editar-usuario"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalUsuario"
                                            data-id="<?= (int)$u['id_usuario'] ?>"
                                            data-nombre="<?= htmlspecialchars($u['nombre_completo']) ?>"
                                            data-username="<?= htmlspecialchars($u['username']) ?>"
                                            data-email="<?= htmlspecialchars($u['email']) ?>"
                                            data-grupo="<?= (int)$u['id_grupo'] ?>"
                                            data-estado="<?= htmlspecialchars($u['estado']) ?>">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>

                                        <!-- Botón Eliminar Usuario -->
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-3 btn-eliminar-usuario"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarUsuario"
                                            data-id="<?= (int)$u['id_usuario'] ?>"
                                            data-nombre="<?= htmlspecialchars($u['nombre_completo']) ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- =========================================================
                 PESTAÑA 2: PERFILES / ROLES (Tabla: grupo)
                 ========================================================= -->
            <div class="tab-pane fade" id="tabRoles" role="tabpanel" aria-labelledby="tab-roles-btn">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-secondary">Perfiles y Roles registrados</h6>
                    <button type="button" class="btn btn-primary btn-sm fw-semibold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalRol" id="btnNuevoRol">
                        <i class="bi bi-plus-lg me-1"></i>Nuevo Rol
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col" class="ps-3">ID Grupo</th>
                                <th scope="col">Nombre del Perfil / Rol</th>
                                <th scope="col">Usuarios Asignados</th>
                                <th scope="col" class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles_lista as $r): ?>
                                <tr>
                                    <td class="ps-3 fw-medium text-secondary">#<?= (int)$r['id_grupo'] ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($r['nombre']) ?></td>
                                    <td><span class="badge bg-secondary-subtle text-secondary-emphasis"><?= (int)$r['total_usuarios'] ?> usuario(s)</span></td>
                                    <td class="text-end pe-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3 me-1 btn-editar-rol"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRol"
                                            data-id="<?= (int)$r['id_grupo'] ?>"
                                            data-nombre="<?= htmlspecialchars($r['nombre']) ?>">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <a href="../controllers/usuarioController.php?accion=eliminar_rol&id=<?= (int)$r['id_grupo'] ?>" class="btn btn-outline-danger btn-sm rounded-3">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- =========================================================
                 PESTAÑA 3: CATÁLOGO DE PERMISOS (Tabla: permiso)
                 ========================================================= -->
            <div class="tab-pane fade" id="tabPermisos" role="tabpanel" aria-labelledby="tab-permisos-btn">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-secondary">Catálogo de Permisos del Sistema</h6>
                    <button type="button" class="btn btn-primary btn-sm fw-semibold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#modalPermiso" id="btnNuevoPermiso">
                        <i class="bi bi-plus-lg me-1"></i>Nuevo Permiso
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col" class="ps-3">ID Permiso</th>
                                <th scope="col">Descripción del Permiso (nombre)</th>
                                <th scope="col">Clave Interna (codename)</th>
                                <th scope="col" class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permisos_lista as $perm): ?>
                                <tr>
                                    <td class="ps-3 fw-medium text-secondary">#<?= (int)$perm['id_permiso'] ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($perm['nombre']) ?></td>
                                    <td><code class="bg-light px-2 py-1 rounded border"><?= htmlspecialchars($perm['codename']) ?></code></td>
                                    <td class="text-end pe-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3 me-1 btn-editar-permiso"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalPermiso"
                                            data-id="<?= (int)$perm['id_permiso'] ?>"
                                            data-nombre="<?= htmlspecialchars($perm['nombre']) ?>"
                                            data-codename="<?= htmlspecialchars($perm['codename']) ?>">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <a href="../controllers/usuarioController.php?accion=eliminar_permiso&id=<?= (int)$perm['id_permiso'] ?>" class="btn btn-outline-danger btn-sm rounded-3">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <!-- =========================================================
         MODAL 1: CREAR / EDITAR USUARIO
         ========================================================= -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow" action="../controllers/usuarioController.php" method="POST">
                <header class="modal-header bg-primary-subtle border-0">
                    <h5 class="modal-title fw-bold text-primary-emphasis" id="modalUsuarioLabel">
                        <i class="bi bi-person-gear me-2"></i>Crear / Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4">
                    <input type="hidden" name="tipo_formulario" value="usuario">
                    <input type="hidden" name="id_usuario" id="id_usuario" value="">

                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label fw-semibold small">Nombre Completo</label>
                        <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" maxlength="60" placeholder="Ej. Dra. Samantha Samudio" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="username" class="form-label fw-semibold small">Nombre de Usuario</label>
                            <input type="text" name="username" id="username" class="form-control" maxlength="30" placeholder="Ej. ssamudio" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="contrasena" class="form-label fw-semibold small">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" maxlength="100" placeholder="correo@cexplus.com" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="id_grupo" class="form-label fw-semibold small">Perfil / Rol (Grupo)</label>
                            <select name="id_grupo" id="id_grupo" class="form-select" required>
                                <option value="" selected disabled>Selecciona un rol...</option>
                                <?php foreach ($roles_lista as $r): ?>
                                    <option value="<?= (int)$r['id_grupo'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="estado_usuario" class="form-label fw-semibold small">Estado</label>
                            <select name="estado" id="estado_usuario" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">Guardar Usuario</button>
                </footer>
            </form>
        </div>
    </div>

    <!-- =========================================================
         MODAL 2: ASIGNAR / REVOCAR PERMISOS A UN USUARIO (Tabla: tiene)
         ========================================================= -->
    <div class="modal fade" id="modalAsignarPermisos" tabindex="-1" aria-labelledby="modalAsignarPermisosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow" action="../controllers/usuarioController.php" method="POST">
                <header class="modal-header bg-primary-subtle border-0">
                    <h5 class="modal-title fw-bold text-primary-emphasis" id="modalAsignarPermisosLabel">
                        <i class="bi bi-key-fill me-2"></i>Permisos del Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4">
                    <input type="hidden" name="tipo_formulario" value="asignar_permisos">
                    <input type="hidden" name="id_usuario" id="permisos_id_usuario" value="">

                    <p class="mb-3 small text-muted">
                        Selecciona o desmarca los permisos para <strong class="text-dark" id="permisos_nombre_usuario"></strong>:
                    </p>

                    <div class="list-group">
                        <?php foreach ($permisos_lista as $perm): ?>
                            <label class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <span class="fw-medium d-block small"><?= htmlspecialchars($perm['nombre']) ?></span>
                                    <code class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($perm['codename']) ?></code>
                                </div>
                                <input class="form-check-input ms-2 check-permiso-usuario" type="checkbox" name="permisos[]" value="<?= (int)$perm['id_permiso'] ?>">
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">Guardar Permisos</button>
                </footer>
            </form>
        </div>
    </div>

    <!-- =========================================================
         MODAL 3: CREAR / EDITAR PERFIL O ROL (Tabla: grupo)
         ========================================================= -->
    <div class="modal fade" id="modalRol" tabindex="-1" aria-labelledby="modalRolLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form class="modal-content border-0 shadow" action="../controllers/usuarioController.php" method="POST">
                <header class="modal-header bg-primary-subtle border-0">
                    <h5 class="modal-title fw-bold text-primary-emphasis" id="modalRolLabel">
                        <i class="bi bi-shield-plus me-2"></i>Perfil / Rol
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4">
                    <input type="hidden" name="tipo_formulario" value="rol">
                    <input type="hidden" name="id_grupo" id="id_grupo_modal" value="">

                    <div class="mb-2">
                        <label for="nombre_grupo" class="form-label fw-semibold small">Nombre del Rol</label>
                        <input type="text" name="nombre" id="nombre_grupo" class="form-control" maxlength="15" placeholder="Ej. Médico" required>
                    </div>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">Guardar</button>
                </footer>
            </form>
        </div>
    </div>

    <!-- =========================================================
         MODAL 4: CREAR / EDITAR PERMISO (Tabla: permiso)
         ========================================================= -->
    <div class="modal fade" id="modalPermiso" tabindex="-1" aria-labelledby="modalPermisoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow" action="../controllers/usuarioController.php" method="POST">
                <header class="modal-header bg-primary-subtle border-0">
                    <h5 class="modal-title fw-bold text-primary-emphasis" id="modalPermisoLabel">
                        <i class="bi bi-key me-2"></i>Crear / Editar Permiso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4">
                    <input type="hidden" name="tipo_formulario" value="permiso">
                    <input type="hidden" name="id_permiso" id="id_permiso_modal" value="">

                    <div class="mb-3">
                        <label for="nombre_permiso" class="form-label fw-semibold small">Nombre descriptivo del Permiso</label>
                        <input type="text" name="nombre" id="nombre_permiso" class="form-control" maxlength="60" placeholder="Ej. Gestionar Pacientes" required>
                    </div>

                    <div class="mb-2">
                        <label for="codename_permiso" class="form-label fw-semibold small">Clave interna (codename)</label>
                        <input type="text" name="codename" id="codename_permiso" class="form-control" maxlength="20" placeholder="Ej. gestionar_pacientes" required>
                    </div>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">Guardar Permiso</button>
                </footer>
            </form>
        </div>
    </div>

    <!-- =========================================================
         MODAL 5: CONFIRMAR ELIMINACIÓN DE USUARIO
         ========================================================= -->
    <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <header class="modal-header bg-danger-subtle border-0">
                    <h5 class="modal-title fw-bold text-danger-emphasis" id="modalEliminarUsuarioLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </header>

                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-trash3 fs-1"></i>
                    </div>
                    <p class="mb-1 fs-6">¿Estás seguro de que deseas eliminar al usuario:</p>
                    <p class="fw-bold fs-5 text-dark mb-0" id="nombreUsuarioEliminar"></p>
                    <small class="text-muted">Esta acción no se puede deshacer.</small>
                </div>

                <footer class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="btnConfirmarEliminarUsuario" class="btn btn-danger rounded-3 px-4 fw-semibold">
                        <i class="bi bi-trash me-1"></i>Sí, eliminar
                    </a>
                </footer>
            </div>
        </div>
    </div>

</main>

<?php
$script_adicional = 'js/configuracion.js';
include_once '../templatesReutilizables/footer.php';
?>