<?php
/**
 * Página de registro de usuarios
 */

// Nivel de carpeta para las rutas relativas
$nivel = 1;
$pageTitle = 'Registro - Torneo de Futsala';
$extraCss = ['registro.css'];

// Incluir el encabezado
include_once '../components/header.php';
?>

<main>
    <section class="registro-container">
        <h2>Registro de Usuario</h2>
        <form id="registro-form">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm-password">Confirmar Contraseña:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            
            <button type="submit" class="btn-submit">Registrarse</button>
            
            <div id="error-message" class="error-message">
                Error al registrar usuario. Por favor, intente nuevamente.
            </div>
            
            <div id="success-message" class="success-message">
                ¡Registro exitoso! Redirigiendo al inicio de sesión...
            </div>
        </form>
        
        <div class="login-link">
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['registro.js'];

// Incluir el pie de página
include_once '../components/footer.php';
?> 