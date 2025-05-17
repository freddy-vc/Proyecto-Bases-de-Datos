document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el usuario está logueado
    checkUserSession();
    // Cargar datos de clasificación
    cargarClasificaciones();
});
/**
 * Verifica si hay una sesión de usuario activa
 */
function checkUserSession() {
    // Verificar si hay datos de sesión en localStorage
    const userData = localStorage.getItem('user');
    if (userData) {
        const user = JSON.parse(userData);
        updateNavForLoggedUser(user);
    }
}
/**
 * Actualiza la navegación para usuarios logueados
 */
function updateNavForLoggedUser(user) {
    const navMenu = document.querySelector('.menu');
    // Remover botones de login y registro
    const loginBtn = document.querySelector('.btn-login')?.parentElement;
    const registerBtn = document.querySelector('.btn-registro')?.parentElement;
    if (loginBtn) loginBtn.remove();
    if (registerBtn) registerBtn.remove();
    // Mostrar nombre de usuario
    const userDisplay = document.createElement('li');
    userDisplay.textContent = `Bienvenido, ${user.username}`;
    navMenu.appendChild(userDisplay);
}
// Aquí iría la función cargarClasificaciones, que debe ser extraída del bloque original si existe.