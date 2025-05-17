<?php
/**
 * Página principal del panel de administración
 */

// Nivel de carpeta para las rutas relativas
$nivel = 2;
$pageTitle = 'Panel de Administración - Torneo de Futsala';
$extraCss = ['admin/admin.css'];

// Verificar si el usuario tiene permiso de administrador
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    // Redirigir al login si no es administrador
    header('Location: ../../pages/login.php');
    exit;
}

// Incluir el encabezado
include_once '../../components/header.php';
?>

<main class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">Panel de Administración</h1>
        <div class="admin-user">
            <img src="../../assets/img/admin-avatar.svg" alt="Avatar de administrador">
            <span>Administrador: <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
        </div>
    </div>
    
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-number" id="total-equipos">-</div>
            <div class="stat-label">Equipos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="total-jugadores">-</div>
            <div class="stat-label">Jugadores</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="total-encuentros">-</div>
            <div class="stat-label">Encuentros</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="total-usuarios">-</div>
            <div class="stat-label">Usuarios</div>
        </div>
    </div>
    
    <div class="admin-menu">
        <div class="admin-menu-item">
            <div class="icon-equipos"></div>
            <h3>Equipos</h3>
            <p>Gestiona los equipos participantes en el torneo.</p>
            <a href="equipos.php">Administrar Equipos</a>
        </div>
        <div class="admin-menu-item">
            <div class="icon-jugadores"></div>
            <h3>Jugadores</h3>
            <p>Administra los jugadores de los equipos.</p>
            <a href="jugadores.php">Administrar Jugadores</a>
        </div>
        <div class="admin-menu-item">
            <div class="icon-encuentros"></div>
            <h3>Encuentros</h3>
            <p>Programa y gestiona los partidos del torneo.</p>
            <a href="encuentros.php">Administrar Encuentros</a>
        </div>
        <div class="admin-menu-item">
            <div class="icon-ciudades"></div>
            <h3>Ciudades</h3>
            <p>Gestiona las ciudades participantes.</p>
            <a href="ciudades.php">Administrar Ciudades</a>
        </div>
        <div class="admin-menu-item">
            <div class="icon-directores"></div>
            <h3>Directores Técnicos</h3>
            <p>Gestiona los directores técnicos de los equipos.</p>
            <a href="directores.php">Administrar Técnicos</a>
        </div>
        <div class="admin-menu-item">
            <div class="icon-usuarios"></div>
            <h3>Usuarios</h3>
            <p>Administra los usuarios del sistema.</p>
            <a href="usuarios.php">Administrar Usuarios</a>
        </div>
    </div>
    
    <div class="admin-actions">
        <h2>Acciones Rápidas</h2>
        <ul class="action-list">
            <li><a href="crear-equipo.php">➕ Crear nuevo equipo</a></li>
            <li><a href="crear-jugador.php">➕ Registrar nuevo jugador</a></li>
            <li><a href="programar-encuentro.php">📆 Programar encuentro</a></li>
            <li><a href="registrar-resultado.php">📊 Registrar resultado</a></li>
            <li><a href="generar-reporte.php">📄 Generar reporte del torneo</a></li>
        </ul>
    </div>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['admin/admin.js'];

// Incluir el pie de página
include_once '../../components/footer.php';
?> 