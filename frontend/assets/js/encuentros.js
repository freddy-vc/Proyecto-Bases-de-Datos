document.addEventListener('DOMContentLoaded', function () {
    cargarEncuentros();
});

function cargarEncuentros() {
    fetch('../../backend/api/encuentros.php?action=listar')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los encuentros');
            }
            return response.json();
        })
        .then(data => {
            mostrarEncuentros(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('encuentros-container').innerHTML = 
                '<p class="error-message">No se pudieron cargar los encuentros</p>';
        });
}

function mostrarEncuentros(encuentros) {
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

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', options);
}