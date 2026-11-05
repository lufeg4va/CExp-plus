
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
?>.

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEx+-REGISTRO</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="Estilos/Login_y_Registro.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 login-bg">

    <main class="login-card bg-white p-4 p-md-5 shadow-sm rounded-4">
        
        <!--ENCABEZADO: Logo y Título -->
        <header class="text-center mb-4">
            <i class="bi bi-heart-pulse logo-icon"></i>
            <h4 class="fw-bold mt-2">Sistema De Gestion <br>
        </header>

        <!--PESTAÑAS -->
        <nav class="custom-tabs mb-4">
            <a href="login.php" class="tab-btn">Iniciar Sesión</a>
            <a href="#" class="tab-btn active">Registrarse</a>
        </nav>

        <!-- FORMULARIO -->
        <form action="#" method="POST">
            
          
            <div class="mb-3 position-relative">
                <label for="email" class="form-label fw-bold small">Correo Electrónico</label>
                
                <i class="bi bi-envelope position-absolute text-primary" style="left: 15px; top: 40px;"></i>
                <input type="email" class="form-control custom-input ps-5" id="email" required>
            </div>

            <!-- Contraseña -->
            <div class="mb-4 position-relative">
                <label for="password" class="form-label fw-bold small">Establecer contraseña</label>
                <i class="bi bi-lock position-absolute text-primary" style="left: 15px; top: 40px;"></i>
                <input type="password" class="form-control custom-input ps-5" id="password" required>
            </div>

            <!-- btnIngresar -->
            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold text-white btn-login">
                Registrarse e ingresar <i class="bi bi-arrow-right ms-1"></i>
            </button>
            
        </form>
    </main>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>