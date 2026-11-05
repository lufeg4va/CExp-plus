<?php
require_once '../config/database.php';
require_once '../models/usuario.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $usuarioModel = new usuario($db);

    $username_ingresado = $_POST['username'];
    $password_ingresada = $_POST['password'];

    $datos_usuario = $usuarioModel->getUsuarioByUsername($username_ingresado);

    // Validar si existe el usuario y verificar contraseña
    // Nota: Aquí validamos directamente, pero a futuro usa password_verify()
    if ($datos_usuario && $password_ingresada === $datos_usuario['contrasena']) {
        if ($datos_usuario['estado'] == 'activo') {
            // Login exitoso
            $_SESSION['id_usuario'] = $datos_usuario['id_usuario'];
            $_SESSION['username']   = $datos_usuario['username'];
            header("Location: ../views/citas.php");
            exit();
        } else {
            header("Location: ../views/login.php?error=cuenta_inactiva");
            exit();
        }
    } else {
        header("Location: ../views/login.php?error=credenciales_incorrectas");
        exit();
    }
}
?>