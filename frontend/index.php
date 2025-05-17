<?php
/**
 * Página principal del sitio
 */

// Nivel de carpeta para las rutas relativas
$nivel = 0;
$pageTitle = 'Torneo de Futsala - Villavicencio';
$extraCss = [];

// Incluir el encabezado
include_once 'components/header.php';
?>

<main>
    <section class="hero">
        <h1>Torneo de Futsala Villavicencio</h1>
        <p>Bienvenido al sitio oficial del torneo de futsala de Villavicencio, Meta.</p>
    </section>

    <section class="proximos-partidos">
        <h2>Próximos Partidos</h2>
        <div class="partidos-container" id="proximos-partidos-container">
            <!-- Aquí se cargarán dinámicamente los próximos partidos -->
            <p class="loading-message">Cargando próximos partidos...</p>
        </div>
    </section>

    <section class="ultimos-resultados">
        <h2>Últimos Resultados</h2>
        <div class="resultados-container" id="ultimos-resultados-container">
            <!-- Aquí se cargarán dinámicamente los últimos resultados -->
            <p class="loading-message">Cargando últimos resultados...</p>
        </div>
    </section>

    <section class="equipos-destacados">
        <h2>Equipos Destacados</h2>
        <div class="equipos-container" id="equipos-destacados-container">
            <!-- Aquí se cargarán dinámicamente los equipos destacados -->
            <p class="loading-message">Cargando equipos destacados...</p>
        </div>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['home.js'];

// Incluir el pie de página
include_once 'components/footer.php';
?> 