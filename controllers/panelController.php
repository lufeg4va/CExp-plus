<?php
require_once '../config/auth_admin.php';
require_once '../config/database.php';
require_once '../models/usuario.php';

//  Conectar a la base de datos 
$database = new Database();
$db = $database->getConnection();
$usuarioModel = new usuario($db);
$grupoModel = new grupo($db);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario     = (int)$_POST['id_usuario'] ?? '';
    $nombre_completo = $_POST['nombre_completo'];
    $username = $_POST['username'];
    $contrasena   = $_POST['contrasena'];
    $email = $_POST['email'];
    $estado      = $_POST['estado'];

    if (empty($id_usuario)) {
        // No hay id_usuario es es una NUEVA cita 
        $userModel->crearUsuario($id_usuario, $nombre_completo, $username, $contrasena, $email, $estado);
    } else {
        // Si hay id_usuario  es ACTUALIZAR una cita existente 
        $userModel->actualizarCita((int)$id_usuario, $nombre_completo, $username, $contrasena, $email, $estado);
    }

    header("Location: ../views/admin_panel.php");
    exit();
}

// Si la petición viene por Metodo GET: Eliminar cita
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['accion'])) {
    if ($_GET['accion'] === 'eliminar' && !empty($_GET['id'])) {
        $id_usuario = (int)$_GET['id'];
        $userModel->eliminarCita($id_usuario);
    }

    header("Location: ../views/admin_panel.php");
    exit();
}
?>