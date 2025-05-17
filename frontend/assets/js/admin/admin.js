/**
 * JavaScript para el panel de administración
 */
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estadísticas del dashboard
    cargarEstadisticas();
});

/**
 * Carga las estadísticas para el dashboard
 */
function cargarEstadisticas() {
    // Obtener elementos donde mostrar los contadores
    const totalEquipos = document.getElementById('total-equipos');
    const totalJugadores = document.getElementById('total-jugadores');
    const totalEncuentros = document.getElementById('total-encuentros');
    const totalUsuarios = document.getElementById('total-usuarios');
    
    // Si no existen los elementos, salir de la función
    if (!totalEquipos || !totalJugadores || !totalEncuentros || !totalUsuarios) {
        return;
    }
    
    // Cargar estadísticas de equipos
    fetchAPI('../../backend/api/estadisticas.php?tipo=equipos')
        .then(data => {
            if (data.status === 'success') {
                totalEquipos.textContent = data.total;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas de equipos:', error);
            totalEquipos.textContent = 'Error';
        });
    
    // Cargar estadísticas de jugadores
    fetchAPI('../../backend/api/estadisticas.php?tipo=jugadores')
        .then(data => {
            if (data.status === 'success') {
                totalJugadores.textContent = data.total;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas de jugadores:', error);
            totalJugadores.textContent = 'Error';
        });
    
    // Cargar estadísticas de encuentros
    fetchAPI('../../backend/api/estadisticas.php?tipo=encuentros')
        .then(data => {
            if (data.status === 'success') {
                totalEncuentros.textContent = data.total;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas de encuentros:', error);
            totalEncuentros.textContent = 'Error';
        });
    
    // Cargar estadísticas de usuarios
    fetchAPI('../../backend/api/estadisticas.php?tipo=usuarios')
        .then(data => {
            if (data.status === 'success') {
                totalUsuarios.textContent = data.total;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas de usuarios:', error);
            totalUsuarios.textContent = 'Error';
        });
}

/**
 * Función para realizar peticiones a la API con manejo de errores
 * 
 * @param {string} url URL de la petición
 * @param {Object} options Opciones de fetch
 * @return {Promise} Promesa con la respuesta
 */
async function fetchAPI(url, options = {}) {
    try {
        const response = await fetch(url, options);
        
        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status} ${response.statusText}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error en la petición fetch:', error);
        throw error;
    }
} 