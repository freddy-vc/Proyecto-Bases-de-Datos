<?php
/**
 * Script para cerrar sesión
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se está usando un cookie de sesión, eliminarlo
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// También eliminar cualquier variable en localStorage (vía JavaScript)
echo "<script>
    localStorage.removeItem('user');
    window.location.href = 'index.php';
</script>";

// Por si JavaScript está deshabilitado, hacer la redirección en PHP también
header("Location: index.php");
exit;
?> 