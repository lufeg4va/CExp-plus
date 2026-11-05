<?php
require_once '../config/database.php';
require_once '../models/usuario.php';
require_once '../models/grupo.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $usuarioModel = new usuario($db);
    $grupoModel = new grupo($db);

    $username_ingresado = $_POST['username'];
    $password_ingresada = $_POST['password'];

    $datos_usuario = $usuarioModel->getUsuarioByUsername($username_ingresado);
    if($datos_usuario){
        $id_grupo = $datos_usuario['id_grupo'];
        $datos_grupo = $grupoModel-> getgrupoById($id_grupo);
    }else{
        $datos_grupo = null;
    }
    // Validar si existe el usuario y verificar contraseña
    // Nota: Aquí validamos directamente, pero a futuro usa password_verify()
    if ($datos_usuario && $password_ingresada === $datos_usuario['contrasena']) {

    if ($datos_usuario['estado'] === 'activo') {

        session_regenerate_id(true);

        $_SESSION['id_usuario']   = $datos_usuario['id_usuario'];
        $_SESSION['username']     = $datos_usuario['username'];
        $_SESSION['id_grupo']     = $datos_usuario['id_grupo'];
        $_SESSION['nombre_grupo'] = $datos_grupo['nombre'] ?? null; //por si no tiene grupo asignado

        if ((int)$datos_usuario['id_grupo'] === 1) {
            header("Location: ../views/admin_panel.php");
        } else {
            header("Location: ../views/citas.php");
        }
        exit();

    } else {
        header("Location: ../views/login.php?error=cuenta_inactiva");
        exit();
    }

}   else {
header("Location: ../views/login.php?error=credenciales_incorrectas");
exit();
    }
}
?>