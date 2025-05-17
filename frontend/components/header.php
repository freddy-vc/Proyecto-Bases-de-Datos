<?php
/**
 * Componente Header para todas las páginas
 * 
 * @param string $pageTitle Título de la página
 * @param int $nivel Nivel de profundidad en la estructura de carpetas (0 para index, 1 para páginas en /pages, etc.)
 * @param array $extraCss Array con rutas adicionales de CSS relativas a assets/css
 */

// Definir la ruta base según el nivel
$baseUrl = $nivel > 0 ? str_repeat('../', $nivel) : './';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Torneo de Futsala - Villavicencio'; ?></title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/styles.css">
    <?php 
    // Incluir archivos CSS adicionales
    if (!empty($extraCss) && is_array($extraCss)) {
        foreach ($extraCss as $cssFile) {
            echo '<link rel="stylesheet" href="' . $baseUrl . 'assets/css/' . $cssFile . '">';
        }
    }
    ?>
</head>
<body>
    <header id="main-header">
        <div class="logo-container">
            <a href="<?php echo $baseUrl; ?>index.php">
                <img src="<?php echo $baseUrl; ?>assets/img/logo.svg" alt="Logo Torneo Futsala" id="logo">
            </a>
        </div>
        <?php include_once 'nav.php'; ?>
    </header>
</body>
</html> 