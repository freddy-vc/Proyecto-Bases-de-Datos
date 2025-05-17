<?php
/**
 * Página de encuentros (partidos)
 */

// Nivel de carpeta para las rutas relativas
$nivel = 1;
$pageTitle = 'Partidos - Torneo de Futsala';
$extraCss = ['encuentros.css'];

// Incluir el encabezado
include_once '../components/header.php';
?>

<main>
    <section class="encuentros-container">
        <h2>Encuentros</h2>
        <div class="partidos-container" id="partidos-container">
            <div class="loading-message">Cargando partidos...</div>
        </div>
        <div id="encuentros-container" class="encuentros-container">
            <!-- Los encuentros se cargarán dinámicamente aquí -->
        </div>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['encuentros.js'];

// Incluir el pie de página
include_once '../components/footer.php';
?> 