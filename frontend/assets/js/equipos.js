/**
 * JavaScript para la página de equipos
 */
document.addEventListener('DOMContentLoaded', function() {
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
 * Carga las ciudades para el filtro
 */
function cargarCiudades() {
    fetch('../../backend/api/ciudades.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const selectCiudad = document.getElementById('filtro-ciudad');
                
                data.data.forEach(ciudad => {
                    const option = document.createElement('option');
                    option.value = ciudad.cod_ciu;
                    option.textContent = ciudad.nombre;
                    selectCiudad.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar ciudades:', error);
        });
}

/**
 * Carga los equipos según el filtro seleccionado
 */
function cargarEquipos() {
    const container = document.getElementById('equipos-container');
    container.innerHTML = '<p class="loading-message">Cargando equipos...</p>';
    
    const filtroCiudad = document.getElementById('filtro-ciudad').value;
    let url = '../../backend/api/equipos.php';
    
    if (filtroCiudad) {
        url += `?ciudad=${filtroCiudad}`;
    }
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                mostrarEquipos(data.data);
            } else {
                container.innerHTML = `<p class="error-message">${data.message || 'Error al cargar equipos'}</p>`;
            }
        })
        .catch(error => {
            console.error('Error al cargar equipos:', error);
            container.innerHTML = '<p class="error-message">Error de conexión. Por favor, intente nuevamente.</p>';
        });
}

/**
 * Muestra los equipos en la página
 */
function mostrarEquipos(equipos) {
    const container = document.getElementById('equipos-container');
    
    if (!equipos || equipos.length === 0) {
        container.innerHTML = '<p>No hay equipos disponibles</p>';
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
                <p><strong>Director Técnico:</strong> ${equipo.dt_nombres ? `${equipo.dt_nombres} ${equipo.dt_apellidos}` : 'No asignado'}</p>
                <a href="equipos.php?id=${equipo.cod_equipo}" class="btn-ver-mas">Ver detalles</a>
            </div>
        </div>
        `;
    });
    
    container.innerHTML = html;
}