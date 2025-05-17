<?php
/**
 * Componente Footer para todas las páginas
 * 
 * @param int $nivel Nivel de profundidad en la estructura de carpetas (0 para index, 1 para páginas en /pages, etc.)
 * @param array $extraJs Array con rutas adicionales de JS relativas a assets/js
 */

// Si no se define la variable $baseUrl, establecerla como "./"
if (!isset($baseUrl)) {
    $baseUrl = './';
}
?>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Torneo de Futsala Villavicencio. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts comunes -->
    <script src="<?php echo $baseUrl; ?>assets/js/main.js"></script>
    
    <?php
    // Incluir scripts JS adicionales
    if (!empty($extraJs) && is_array($extraJs)) {
        foreach ($extraJs as $jsFile) {
            echo '<script src="' . $baseUrl . 'assets/js/' . $jsFile . '"></script>';
        }
    }
    ?>
</body>
</html> 