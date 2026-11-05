<?php
session_start();

// Si ya inició sesión, enviarlo directo al panel de citas
if (isset($_SESSION['id_usuario'])) {
    header("Location: citas.php");
    exit();
}

// Capturar posibles mensajes de error enviados por el loginController
$error = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'cuenta_inactiva') {
        $error = "Tu cuenta se encuentra inactiva. Contacta al administrador.";
    } elseif ($_GET['error'] === 'credenciales_incorrectas') {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEx+ - Iniciar Sesión</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Estilos de Login -->
    <link rel="stylesheet" href="Estilos/Login_y_Registro.css">

</head>

<body class="d-flex justify-content-center align-items-center vh-100 login-bg">

    <main class="login-card bg-white p-4 p-md-5 shadow-sm rounded-4">
        
        <!-- ENCABEZADO: Logo y Título -->
        <header class="text-center mb-4">
            <i class="bi bi-heart-pulse logo-icon"></i>
            <h4 class="fw-bold mt-2">Sistema De Gestión</h4>
        </header>

        <!-- PESTAÑAS -->
        <nav class="custom-tabs mb-4">
            <a href="login.php" class="tab-btn active">Iniciar Sesión</a>
            <a href="registro.php" class="tab-btn">Registrarse</a>
        </nav>

        <!-- ALERTA DE ERROR -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <!-- FORMULARIO CONECTADO AL CONTROLADOR MVC -->
        <form action="../controllers/loginController.php" method="POST">
            
            <!-- Campo: Username -->
            <div class="mb-3 position-relative">
                <label for="username" class="form-label fw-bold small">Nombre de Usuario</label>
                <i class="bi bi-person position-absolute text-primary" style="left: 15px; top: 40px;"></i>
                <input type="text" name="username" class="form-control custom-input ps-5" id="username" 
                       placeholder="Ingresa tu usuario (ej: adminPRO)" required>
            </div>

            <!-- Campo: Contraseña -->
            <div class="mb-4 position-relative">
                <label for="password" class="form-label fw-bold small">Contraseña</label>
                <i class="bi bi-lock position-absolute text-primary" style="left: 15px; top: 40px;"></i>
                <input type="password" name="password" class="form-control custom-input ps-5" id="password" 
                       placeholder="••••••••" required>
            </div>

            <!-- Botón de Ingresar -->
            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold text-white btn-login">
                Ingresar al sistema <i class="bi bi-arrow-right ms-1"></i>
            </button>
            
        </form>

        <footer class="text-center mt-4">
            <a href="#" class="text-muted text-decoration-none small">Restablecer contraseña</a>
        </footer>

    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
