<?php
session_start();

// no esta logeado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

// logeado pero en el admin (id_grupo 1)
if (!isset($_SESSION['id_grupo']) || $_SESSION['id_grupo'] != 1) {
    header("Location: ../views/citas.php");
    exit();
}