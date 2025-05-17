<?php
/**
 * Página de clasificaciones del torneo
 */

// Nivel de carpeta para las rutas relativas
$nivel = 1;
$pageTitle = 'Clasificaciones - Torneo de Futsala';
$extraCss = ['clasificaciones.css'];

// Incluir el encabezado
include_once '../components/header.php';
?>

<main class="clasificaciones-container">
    <h1>Clasificaciones del Torneo</h1>
    <p>Consulta las fases de eliminación y los equipos clasificados en el torneo de futsala de Villavicencio.</p>
    
    <section class="bracket-section">
        <h2 class="fase-title">Cuadro de Eliminación</h2>
        
        <div class="bracket">
            <div class="bracket-round">
                <h3 class="bracket-title">Cuartos de Final</h3>
                <div id="cuartos-container">
                    <p class="loading-message">Cargando cuartos de final...</p>
                </div>
            </div>
            
            <div class="bracket-connector">
                <div class="connector-line"></div>
                <div class="connector-line"></div>
            </div>
            
            <div class="bracket-round">
                <h3 class="bracket-title">Semifinales</h3>
                <div id="semis-container">
                    <p class="loading-message">Cargando semifinales...</p>
                </div>
            </div>
            
            <div class="bracket-connector">
                <div class="connector-line"></div>
            </div>
            
            <div class="bracket-round">
                <h3 class="bracket-title">Final</h3>
                <div id="final-container">
                    <p class="loading-message">Cargando final...</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="fase-equipos">
        <h2 class="fase-title">Equipos por Fase</h2>
        
        <h3>Cuartos de Final</h3>
        <table class="fase-table" id="tabla-cuartos">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Ciudad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="loading-message">Cargando equipos...</td>
                </tr>
            </tbody>
        </table>
        
        <h3>Semifinales</h3>
        <table class="fase-table" id="tabla-semis">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Ciudad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="loading-message">Cargando equipos...</td>
                </tr>
            </tbody>
        </table>
        
        <h3>Final</h3>
        <table class="fase-table" id="tabla-final">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Ciudad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="loading-message">Cargando equipos...</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['clasificaciones.js'];

// Incluir el pie de página
include_once '../components/footer.php';
?> 