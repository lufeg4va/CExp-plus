<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEx+ - Iniciar Sesión</title>
    
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../Estilos/Login_y_Registro.css">
</head>

<body class="pagina-acceso">

    <main class="tarjeta-acceso">
        
        <!--  Logo y Título -->
        <header class="cabecera-tarjeta">
            <i class="bi bi-heart-pulse icono-logo"></i>
            <h3 class="titulo-tarjeta">Sistema De Gestión</h3>
        </header>

        <!-- PESTAÑAS -->
        <nav class="pestanas-navegacion">
            <a href="login.php" class="boton-pestana Activa">Iniciar Sesión</a>
            <a href="registro.php" class="boton-pestana">Registrarse</a>
        </nav>

        <!-- MENSAJE DE ERROR (si falla el login) -->
        <?php if (isset($_GET['error'])): ?>
            <div style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #f87171; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 0.88rem; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>
                    <?php
                    if ($_GET['error'] === 'credenciales_incorrectas') {
                        echo 'Usuario o contraseña incorrectos.';
                    } elseif ($_GET['error'] === 'cuenta_inactiva') {
                        echo 'Esta cuenta se encuentra inactiva.';
                    } else {
                        echo 'Error al iniciar sesión.';
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form action="../controllers/loginController.php" method="POST">
            
            <!-- CAMPO :Username -->
            <div class="grupo-campo">
                <label for="username" class="etiqueta-campo">Nombre de Usuario</label>
                <i class="bi bi-person icono-campo"></i>
                <input type="text" name="username" class="campo-texto" id="username" 
                       placeholder="Ingresa tu usuario (ej: adminPRO)" required>
            </div>

            <!-- BtnContraseña -->
            <div class="grupo-campo">
                <label for="password" class="etiqueta-campo">Contraseña</label>
                <i class="bi bi-lock icono-campo"></i>
                <input type="password" name="password" class="campo-texto" id="password" 
                       placeholder="••••••••" required>
            </div>

        <!-- BtnIngresar-->  
            <button type="submit" class="boton-primario">
                Ingresar al sistema <i class="bi bi-arrow-right"></i>
            </button>
            
        </form>

        <footer class="pie-tarjeta">
            <a href="#" class="enlace-olvido">Restablecer contraseña</a>
        </footer>

    </main>

</body>
</html>
