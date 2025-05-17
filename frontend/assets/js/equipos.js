document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el usuario está logueado
    checkUserSession();
    // Cargar ciudades para el filtro
    cargarCiudades();
    // Cargar equipos
    cargarEquipos();
    // Configurar evento del botón de filtro
    document.getElementById('btn-filtrar').addEventListener('click', function() {
        cargarEquipos();
    });
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
// Aquí irían las funciones cargarCiudades y cargarEquipos, que deben ser extraídas del bloque original si existen.