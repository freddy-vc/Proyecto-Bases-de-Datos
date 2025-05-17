<?php
/**
 * Página de administración de usuarios
 */

// Nivel de carpeta para las rutas relativas
$nivel = 2;
$pageTitle = 'Administración de Usuarios - Torneo de Futsala';
$extraCss = ['admin/admin.css', 'admin/usuarios.css'];

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

<main>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Administración de Usuarios</h1>
            <div class="admin-user">
                <img id="admin-avatar" src="../../assets/img/admin-avatar.svg" alt="Avatar">
                <span id="admin-username"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
            </div>
        </div>
        
        <button id="add-usuario-btn" class="btn-action btn-add">Agregar Usuario</button>
        
        <table class="usuarios-table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usuarios-table-body">
                <tr>
                    <td colspan="6" class="loading-message">Cargando usuarios...</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Modal para agregar/editar usuario -->
    <div id="usuario-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modal-title">Agregar Usuario</h2>
            <form id="usuario-form">
                <input type="hidden" id="usuario-id">
                
                <div class="form-group">
                    <label for="username">Nombre de Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                    <small id="password-hint">(Dejar en blanco para mantener la contraseña actual al editar)</small>
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="user">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Guardar</button>
                
                <div id="error-message" class="error-message"></div>
            </form>
        </div>
    </div>
    
    <!-- Modal de confirmación para eliminar -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirmar Eliminación</h2>
            <p>¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.</p>
            <div class="modal-buttons">
                <button id="confirm-delete-btn" class="btn-action btn-delete">Eliminar</button>
                <button id="cancel-delete-btn" class="btn-action">Cancelar</button>
            </div>
        </div>
    </div>
</main>

<?php 
// Scripts adicionales específicos de esta página
$extraJs = ['admin/usuarios.js'];

// Incluir el pie de página
include_once '../../components/footer.php';
?> 