<?php
/**
 * Página de inicio de sesión
 */

// Nivel de carpeta para las rutas relativas
$nivel = 1;
$pageTitle = 'Iniciar Sesión - Torneo de Futsala';
$extraCss = ['login.css'];

// Incluir el encabezado
include_once '../components/header.php';
?>

<main>
    <section class="login-container">
        <h2>Iniciar Sesión</h2>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-submit">Iniciar Sesión</button>
            
            <div id="error-message" class="error-message">
                Usuario o contraseña incorrectos. Por favor, intente nuevamente.
            </div>
        </form>
        
        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['login.js'];

// Incluir el pie de página
include_once '../components/footer.php';
?> 