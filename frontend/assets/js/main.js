/**
 * Archivo JavaScript principal para la aplicación de Torneo de Futsala
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el usuario está logueado
    checkUserSession();
    
    // Cargar contenido dinámico según la página actual
    loadPageContent();
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
    const loginBtn = document.querySelector('.btn-login').parentElement;
    const registerBtn = document.querySelector('.btn-registro').parentElement;
    
    if (loginBtn && registerBtn) {
        navMenu.removeChild(loginBtn);
        navMenu.removeChild(registerBtn);
        
        // Agregar nombre de usuario y botón de cerrar sesión
        const userLi = document.createElement('li');
        userLi.innerHTML = `<span class="username">Hola, ${user.username}</span>`;
        
        const logoutLi = document.createElement('li');
        logoutLi.innerHTML = `<a href="#" class="btn-logout">Cerrar Sesión</a>`;
        logoutLi.querySelector('a').addEventListener('click', logout);
        
        navMenu.appendChild(userLi);
        navMenu.appendChild(logoutLi);
        
        // Si es admin, agregar enlace al panel de administración
        if (user.rol === 'admin') {
            const adminLi = document.createElement('li');
            adminLi.innerHTML = `<a href="pages/admin/index.html" class="btn-admin">Panel Admin</a>`;
            navMenu.appendChild(adminLi);
        }
    }
}

/**
 * Cierra la sesión del usuario
 */
function logout(e) {
    e.preventDefault();
    localStorage.removeItem('user');
    window.location.href = 'index.html';
}

/**
 * Carga el contenido específico de cada página
 */
function loadPageContent() {
    const currentPage = window.location.pathname.split('/').pop();
    
    switch(currentPage) {
        case '':
        case 'index.html':
            loadHomePageContent();
            break;
        case 'equipos.html':
            loadEquiposContent();
            break;
        case 'jugadores.html':
            loadJugadoresContent();
            break;
        case 'encuentros.html':
            loadEncuentrosContent();
            break;
        case 'clasificaciones.html':
            loadClasificacionesContent();
            break;
    }
}

/**
 * Carga el contenido de la página principal
 */
function loadHomePageContent() {
    // Cargar próximos partidos
    fetch('../backend/api/encuentros.php?action=proximos')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            displayProximosPartidos(data);
        })
        .catch(error => {
            console.error('Error al cargar próximos partidos:', error);
            document.getElementById('proximos-partidos-container').innerHTML = 
                '<p class="error-message">No se pudieron cargar los próximos partidos</p>';
        });
    
    // Cargar últimos resultados
    fetch('../backend/api/encuentros.php?action=ultimos')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            displayUltimosResultados(data);
        })
        .catch(error => {
            console.error('Error al cargar últimos resultados:', error);
            document.getElementById('ultimos-resultados-container').innerHTML = 
                '<p class="error-message">No se pudieron cargar los últimos resultados</p>';
        });
    
    // Cargar equipos destacados
    fetch('../backend/api/equipos.php?action=destacados')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            displayEquiposDestacados(data);
        })
        .catch(error => {
            console.error('Error al cargar equipos destacados:', error);
            document.getElementById('equipos-destacados-container').innerHTML = 
                '<p class="error-message">No se pudieron cargar los equipos destacados</p>';
        });
}

/**
 * Carga el contenido de la página de encuentros
 */
function loadEncuentrosContent() {
    fetch('../backend/api/encuentros.php?action=listar')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los encuentros');
            }
            return response.json();
        })
        .then(data => {
            displayEncuentros(data);
        })
        .catch(error => {
            console.error('Error al cargar encuentros:', error);
            document.getElementById('encuentros-container').innerHTML = 
                '<p class="error-message">No se pudieron cargar los encuentros</p>';
        });
}

/**
 * Muestra los encuentros en la página
 */
function displayEncuentros(encuentros) {
    const container = document.getElementById('encuentros-container');
    if (!encuentros || encuentros.length === 0) {
        container.innerHTML = '<p>No hay encuentros programados</p>';
        return;
    }

    let html = '';
    encuentros.forEach(encuentro => {
        html += `
            <div class="encuentro-card">
                <div class="encuentro-fecha">${formatDate(encuentro.fecha)} - ${encuentro.hora}</div>
                <div class="encuentro-equipos">
                    <span class="equipo-local">${encuentro.equipo_local}</span>
                    <span class="vs">VS</span>
                    <span class="equipo-visitante">${encuentro.equipo_visitante}</span>
                </div>
                <div class="encuentro-lugar">${encuentro.cancha}</div>
                <div class="encuentro-estado">${encuentro.estado}</div>
            </div>
        `;
    });

    container.innerHTML = html;
}

/**
 * Muestra los próximos partidos en la página principal
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
                <div class="partido-fecha">${formatDate(partido.fecha)} - ${partido.hora}</div>
                <div class="partido-equipos">
                    <span class="equipo-local">${partido.equipo_local}</span>
                    <span class="vs">VS</span>
                    <span class="equipo-visitante">${partido.equipo_visitante}</span>
                </div>
                <div class="partido-lugar">${partido.cancha}</div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Muestra los últimos resultados en la página principal
 */
function displayUltimosResultados(resultados) {
    const container = document.getElementById('ultimos-resultados-container');
    
    if (!resultados || resultados.length === 0) {
        container.innerHTML = '<p>No hay resultados disponibles</p>';
        return;
    }
    
    let html = '';
    
    resultados.forEach(resultado => {
        html += `
            <div class="resultado-card">
                <div class="resultado-fecha">${formatDate(resultado.fecha)}</div>
                <div class="resultado-equipos">
                    <span class="equipo-local">${resultado.equipo_local}</span>
                    <span class="resultado">${resultado.goles_local} - ${resultado.goles_visitante}</span>
                    <span class="equipo-visitante">${resultado.equipo_visitante}</span>
                </div>
                <div class="resultado-lugar">${resultado.cancha}</div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Muestra los equipos destacados en la página principal
 */
function displayEquiposDestacados(equipos) {
    const container = document.getElementById('equipos-destacados-container');
    
    if (!equipos || equipos.length === 0) {
        container.innerHTML = '<p>No hay equipos destacados disponibles</p>';
        return;
    }
    
    let html = '';
    
    equipos.forEach(equipo => {
        html += `
            <div class="equipo-card">
                <div class="equipo-logo">
                    <img src="${equipo.escudo ? 'data:image/png;base64,' + equipo.escudo : 'assets/img/equipo-default.svg'}" alt="${equipo.nombre}">
                </div>
                <h3 class="equipo-nombre">${equipo.nombre}</h3>
                <div class="equipo-ciudad">${equipo.ciudad}</div>
                <a href="pages/equipos.html?id=${equipo.cod_equ}" class="btn-ver-mas">Ver más</a>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Formatea una fecha en formato legible
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', options);
}