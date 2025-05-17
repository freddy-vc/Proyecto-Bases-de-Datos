/**
 * Archivo JavaScript principal para la aplicación de Torneo de Futsala
 */

document.addEventListener('DOMContentLoaded', function() {
    // Manejar sesión (ahora se maneja desde PHP)
    manejarSesion();
    
    // Cargar contenido dinámico según la página actual
    loadPageContent();
});

/**
 * Funcionalidad para manejar la sesión del usuario
 * Esta función está vacía porque ahora el manejo de sesión
 * se realiza completamente desde PHP en nav.php
 */
function manejarSesion() {
    // Esta función ahora está vacía porque el manejo de la sesión
    // se realiza desde el servidor con PHP en nav.php
}

/**
 * Carga el contenido específico de cada página
 */
function loadPageContent() {
    // Obtener la página actual
    const path = window.location.pathname;
    const page = path.split('/').pop();
    
    // Cargar contenido según la página
    if (path.endsWith('/') || page === 'index.php') {
        loadHomePageContent();
    } else if (page === 'equipos.php') {
        // No es necesario hacer nada ya que equipos.js maneja su propio contenido
    } else if (page === 'jugadores.php') {
        // No es necesario hacer nada ya que jugadores.js maneja su propio contenido
    } else if (page === 'encuentros.php') {
        // No es necesario hacer nada ya que encuentros.js maneja su propio contenido
    } else if (page === 'clasificaciones.php') {
        // No es necesario hacer nada ya que clasificaciones.js maneja su propio contenido
    }
}

/**
 * Carga el contenido de la página principal
 */
function loadHomePageContent() {
    // Cargar próximos partidos
    fetchAPI('./backend/api/encuentros.php?proximos=true')
        .then(data => {
            if (data.status === 'success') {
                displayProximosPartidos(data.data);
            } else {
                document.getElementById('proximos-partidos-container').innerHTML = 
                    '<p class="error-message">No se pudieron cargar los próximos partidos</p>';
            }
        })
        .catch(error => {
            console.error('Error al cargar próximos partidos:', error);
            document.getElementById('proximos-partidos-container').innerHTML = 
                '<p class="error-message">Error de conexión. Por favor, intente nuevamente.</p>';
        });
    
    // Cargar últimos resultados
    fetchAPI('./backend/api/encuentros.php?ultimos=true')
        .then(data => {
            if (data.status === 'success') {
                displayUltimosResultados(data.data);
            } else {
                document.getElementById('ultimos-resultados-container').innerHTML = 
                    '<p class="error-message">No se pudieron cargar los últimos resultados</p>';
            }
        })
        .catch(error => {
            console.error('Error al cargar últimos resultados:', error);
            document.getElementById('ultimos-resultados-container').innerHTML = 
                '<p class="error-message">Error de conexión. Por favor, intente nuevamente.</p>';
        });
    
    // Cargar equipos destacados
    fetchAPI('./backend/api/equipos.php?destacados=true')
        .then(data => {
            if (data.status === 'success') {
                displayEquiposDestacados(data.data);
            } else {
                document.getElementById('equipos-destacados-container').innerHTML = 
                    '<p class="error-message">No se pudieron cargar los equipos destacados</p>';
            }
        })
        .catch(error => {
            console.error('Error al cargar equipos destacados:', error);
            document.getElementById('equipos-destacados-container').innerHTML = 
                '<p class="error-message">Error de conexión. Por favor, intente nuevamente.</p>';
        });
}

/**
 * Muestra los próximos partidos en la página
 */
function displayProximosPartidos(partidos) {
    const container = document.getElementById('proximos-partidos-container');
    
    if (!partidos || partidos.length === 0) {
        container.innerHTML = '<p>No hay próximos partidos programados</p>';
        return;
    }
    
    let html = '';
    
    partidos.forEach(partido => {
        html += `
        <div class="partido-card">
            <div class="partido-fecha">${formatearFecha(partido.fecha)} - ${formatearHora(partido.hora)}</div>
            <div class="partido-equipos">
                <span class="equipo-local">${partido.equipo_local}</span>
                <span class="vs">VS</span>
                <span class="equipo-visitante">${partido.equipo_visitante}</span>
            </div>
            <div class="partido-lugar">${partido.cancha}</div>
            <a href="./pages/encuentros.php?id=${partido.id}" class="btn-ver-mas">Ver detalles</a>
        </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Muestra los últimos resultados en la página
 */
function displayUltimosResultados(resultados) {
    const container = document.getElementById('ultimos-resultados-container');
    
    if (!resultados || resultados.length === 0) {
        container.innerHTML = '<p>No hay resultados recientes</p>';
        return;
    }
    
    let html = '';
    
    resultados.forEach(resultado => {
        html += `
        <div class="resultado-card">
            <div class="resultado-fecha">${formatearFecha(resultado.fecha)}</div>
            <div class="resultado-equipos">
                <span class="equipo-local">${resultado.equipo_local}</span>
                <span class="resultado">${resultado.goles_local} - ${resultado.goles_visitante}</span>
                <span class="equipo-visitante">${resultado.equipo_visitante}</span>
            </div>
            <a href="./pages/encuentros.php?id=${resultado.id}" class="btn-ver-mas">Ver detalles</a>
        </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Muestra los equipos destacados en la página
 */
function displayEquiposDestacados(equipos) {
    const container = document.getElementById('equipos-destacados-container');
    
    if (!equipos || equipos.length === 0) {
        container.innerHTML = '<p>No hay equipos destacados</p>';
        return;
    }
    
    let html = '';
    
    equipos.forEach(equipo => {
        html += `
        <div class="equipo-card">
            <div class="equipo-header">
                ${equipo.escudo ? `<img src="data:image/png;base64,${equipo.escudo}" alt="Escudo ${equipo.nombre}" class="equipo-escudo">` : '<div class="equipo-escudo-placeholder"></div>'}
                <h3>${equipo.nombre}</h3>
            </div>
            <div class="equipo-info">
                <p><strong>Ciudad:</strong> ${equipo.nombre_ciudad}</p>
                <p><strong>Puntos:</strong> ${equipo.puntos || 0}</p>
                <a href="./pages/equipos.php?id=${equipo.cod_equipo}" class="btn-ver-mas">Ver detalles</a>
            </div>
        </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Formatear fecha para mostrar
 * 
 * @param {string} fechaStr Fecha en formato ISO
 * @return {string} Fecha formateada
 */
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

/**
 * Formatear hora para mostrar
 * 
 * @param {string} horaStr Hora en formato HH:MM:SS
 * @return {string} Hora formateada
 */
function formatearHora(horaStr) {
    if (!horaStr) return '';
    
    const partes = horaStr.split(':');
    if (partes.length < 2) return horaStr;
    
    return `${partes[0]}:${partes[1]}`;
}

/**
 * Realiza petición fetch con manejo de errores
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

/**
 * Muestra una notificación temporal
 * 
 * @param {string} mensaje Mensaje a mostrar
 * @param {string} tipo Tipo de notificación ('success', 'error', 'info')
 * @param {number} duracion Duración en milisegundos
 */
function mostrarNotificacion(mensaje, tipo = 'info', duracion = 3000) {
    // Eliminar notificaciones previas
    const notificacionesExistentes = document.querySelectorAll('.notificacion');
    notificacionesExistentes.forEach(n => n.remove());
    
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion ${tipo}`;
    notificacion.textContent = mensaje;
    
    // Agregar botón de cerrar
    const btnCerrar = document.createElement('span');
    btnCerrar.className = 'notificacion-cerrar';
    btnCerrar.innerHTML = '&times;';
    btnCerrar.addEventListener('click', () => notificacion.remove());
    
    notificacion.appendChild(btnCerrar);
    document.body.appendChild(notificacion);
    
    // Animar entrada
    setTimeout(() => {
        notificacion.style.opacity = '1';
        notificacion.style.transform = 'translateY(0)';
    }, 10);
    
    // Eliminar después de la duración especificada
    if (duracion > 0) {
        setTimeout(() => {
            notificacion.style.opacity = '0';
            notificacion.style.transform = 'translateY(-20px)';
            setTimeout(() => notificacion.remove(), 300);
        }, duracion);
    }
}