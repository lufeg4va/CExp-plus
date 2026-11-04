<?php
require_once '../config/database.php';
require_once '../models/cita.php';

//  Conectar a la base de datos 
$database = new Database();
$db = $database->getConnection();
$citaModel = new cita($db);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cita     = $_POST['id_cita'] ?? '';
    $id_paciente = (int)$_POST['id_paciente'];
    
    $fecha       = str_replace('T', ' ', $_POST['fecha']);
    $estado      = $_POST['estado'];

    if (empty($id_cita)) {
        // No hay id_cita es es una NUEVA cita 
        $citaModel->crearCita($id_paciente, $fecha, $estado);
    } else {
        // Si hay id_cita  es ACTUALIZAR una cita existente 
        $citaModel->actualizarCita((int)$id_cita, $id_paciente, $fecha, $estado);
    }

    header("Location: ../views/citas.php");
    exit();
}

// Si la petición viene por Metodo GET: Eliminar cita
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['accion'])) {
    if ($_GET['accion'] === 'eliminar' && !empty($_GET['id'])) {
        $id_cita = (int)$_GET['id'];
        $citaModel->eliminarCita($id_cita);
    }

    header("Location: ../views/citas.php");
    exit();
}
?>

