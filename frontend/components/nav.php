<?php
/**
 * Componente de navegación para todas las páginas
 */

// Si no se define la variable $baseUrl, establecerla como "./"
if (!isset($baseUrl)) {
    $baseUrl = './';
}
?>
<nav>
    <ul class="menu">
        <li><a href="<?php echo $baseUrl; ?>index.php">Inicio</a></li>
        <li><a href="<?php echo $baseUrl; ?>pages/clasificaciones.php">Clasificaciones</a></li>
        <li><a href="<?php echo $baseUrl; ?>pages/encuentros.php">Partidos</a></li>
        <li><a href="<?php echo $baseUrl; ?>pages/equipos.php">Equipos</a></li>
        <li><a href="<?php echo $baseUrl; ?>pages/jugadores.php">Jugadores</a></li>
        
        <?php
        // Verificar si hay sesión activa para mostrar botones de login/registro o información del usuario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user'])) {
            // Usuario logueado
            echo '<li><span class="username">Hola, ' . htmlspecialchars($_SESSION['user']['username']) . '</span></li>';
            echo '<li><a href="' . $baseUrl . 'logout.php" class="btn-logout">Cerrar Sesión</a></li>';
            
            // Si es admin, mostrar enlace al panel de administración
            if (isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === 'admin') {
                echo '<li><a href="' . $baseUrl . 'pages/admin/index.php" class="btn-admin">Panel Admin</a></li>';
            }
        } else {
            // Usuario no logueado, mostrar opciones de login y registro
            echo '<li><a href="' . $baseUrl . 'pages/login.php" class="btn-login">Iniciar Sesión</a></li>';
            echo '<li><a href="' . $baseUrl . 'pages/registro.php" class="btn-registro">Registro</a></li>';
        }
        ?>
    </ul>
</nav> 