<?php
/**
 * Página de equipos
 */

// Nivel de carpeta para las rutas relativas
$nivel = 1;
$pageTitle = 'Equipos - Torneo de Futsala';
$extraCss = ['equipos.css'];

// Incluir el encabezado
include_once '../components/header.php';
?>

<main>
    <section>
        <h1>Equipos Participantes</h1>
        <p>Conoce a todos los equipos que participan en el torneo de futsala de Villavicencio.</p>
        
        <div class="filtros">
            <h3>Filtrar por:</h3>
            <select id="filtro-ciudad">
                <option value="">Todas las ciudades</option>
                <!-- Se cargarán dinámicamente -->
            </select>
            <button id="btn-filtrar">Filtrar</button>
        </div>
        
        <div class="equipos-container" id="equipos-container">
            <p class="loading-message">Cargando equipos...</p>
        </div>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['equipos.js'];

// Incluir el pie de página
include_once '../components/footer.php';
?> 