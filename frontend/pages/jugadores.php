<?php
/**
 * Página de jugadores
 */

// Nivel de carpeta para las rutas relativas
$nivel = 1;
$pageTitle = 'Jugadores - Torneo de Futsala';
$extraCss = ['jugadores.css'];

// Incluir el encabezado
include_once '../components/header.php';
?>

<main>
    <section>
        <h1>Jugadores del Torneo</h1>
        <p>Consulta la lista de jugadores participantes, sus estadísticas y fotos.</p>
        <div id="jugadores-container">
            <div class="loading-message">Cargando jugadores...</div>
        </div>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['jugadores.js'];

// Incluir el pie de página
include_once '../components/footer.php';
?> 