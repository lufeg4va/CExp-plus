document.addEventListener('DOMContentLoaded', function () {
    // 1. FILTROS EN VIVO DE LA TABLA DE USUARIOS
    const inputBuscar = document.getElementById('filtroBuscarUsuario');
    const selectRol = document.getElementById('filtroRolUsuario');
    const selectEstado = document.getElementById('filtroEstadoUsuario');
    const filasUsuarios = document.querySelectorAll('#tablaUsuarios tbody tr');

    function filtrarTablaUsuarios() {
        const texto = (inputBuscar?.value || '').toLowerCase().trim();
        const rol = (selectRol?.value || '').toLowerCase();
        const estado = (selectEstado?.value || '').toLowerCase();

        filasUsuarios.forEach(fila => {
            const contenidoFila = fila.textContent.toLowerCase();
            const rolFila = (fila.getAttribute('data-rol') || '').toLowerCase();
            const estadoFila = (fila.getAttribute('data-estado') || '').toLowerCase();

            const coincideTexto = !texto || contenidoFila.includes(texto);
            const coincideRol = !rol || rolFila === rol;
            const coincideEstado = !estado || estadoFila === estado;

            fila.style.display = (coincideTexto && coincideRol && coincideEstado) ? '' : 'none';
        });
    }

    inputBuscar?.addEventListener('input', filtrarTablaUsuarios);
    selectRol?.addEventListener('change', filtrarTablaUsuarios);
    selectEstado?.addEventListener('change', filtrarTablaUsuarios);

    // CREAR / EDITAR USUARIO
    const modalUsuario = document.getElementById('modalUsuario');
    modalUsuario?.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget;
        const idUsuario = boton?.getAttribute('data-id') || '';

        document.getElementById('id_usuario').value = idUsuario;
        document.getElementById('nombre_completo').value = boton?.getAttribute('data-nombre') || '';
        document.getElementById('username').value = boton?.getAttribute('data-username') || '';
        document.getElementById('email').value = boton?.getAttribute('data-email') || '';
        document.getElementById('id_grupo').value = boton?.getAttribute('data-grupo') || '';
        document.getElementById('estado_usuario').value = boton?.getAttribute('data-estado') || 'activo';
        document.getElementById('contrasena').value = '';
        document.getElementById('contrasena').required = (idUsuario === '');
    });

    // ASIGNAR PERMISOS A UN USUARIO
    const modalPermisos = document.getElementById('modalAsignarPermisos');
    modalPermisos?.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget;
        const idUsuario = boton?.getAttribute('data-id') || '';
        const nombreUsuario = boton?.getAttribute('data-nombre') || '';
        const permisosRaw = boton?.getAttribute('data-permisos') || '[]';
        const permisosActivos = JSON.parse(permisosRaw);

        document.getElementById('permisos_id_usuario').value = idUsuario;
        document.getElementById('permisos_nombre_usuario').textContent = nombreUsuario;

        document.querySelectorAll('.check-permiso-usuario').forEach(check => {
            check.checked = permisosActivos.includes(parseInt(check.value, 10));
        });
    });

    // CREAR / EDITAR ROL 
    const modalRol = document.getElementById('modalRol');
    modalRol?.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget;
        document.getElementById('id_grupo_modal').value = boton?.getAttribute('data-id') || '';
        document.getElementById('nombre_grupo').value = boton?.getAttribute('data-nombre') || '';
    });

    // CREAR / EDITAR PERMISO
    const modalPermiso = document.getElementById('modalPermiso');
    modalPermiso?.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget;
        document.getElementById('id_permiso_modal').value = boton?.getAttribute('data-id') || '';
        document.getElementById('nombre_permiso').value = boton?.getAttribute('data-nombre') || '';
        document.getElementById('codename_permiso').value = boton?.getAttribute('data-codename') || '';
    });

    //  CONFIRMAR ELIMINACIÓN DE USUARIO
    const modalEliminar = document.getElementById('modalEliminarUsuario');
    modalEliminar?.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget;
        const idUsuario = boton?.getAttribute('data-id') || '';
        const nombreUsuario = boton?.getAttribute('data-nombre') || '';

        document.getElementById('nombreUsuarioEliminar').textContent = nombreUsuario;
        document.getElementById('btnConfirmarEliminarUsuario').href = `../controllers/usuarioController.php?accion=eliminar&id=${idUsuario}`;
    });
});

