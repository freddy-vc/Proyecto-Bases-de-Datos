document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('jugadores-container');
    fetch('../../backend/api/jugadores.php')
        .then(response => response.json())
        .then(data => {
            container.innerHTML = '';
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="loading-message">No hay jugadores registrados.</div>';
                return;
            }
            data.forEach(jugador => {
                const card = document.createElement('div');
                card.className = 'jugador-card';
                card.innerHTML = `
                    <img class="jugador-foto" src="${jugador.foto || '../assets/img/default-user.png'}" alt="Foto de ${jugador.nombre}">
                    <div class="jugador-info">
                        <div class="jugador-nombre">${jugador.nombre}</div>
                        <div class="jugador-posicion"><strong>Posición:</strong> ${jugador.posicion}</div>
                        <div class="jugador-equipo"><strong>Equipo:</strong> ${jugador.equipo}</div>
                    </div>
                `;
                container.appendChild(card);
            });
        })
        .catch(error => {
            container.innerHTML = '<div class="loading-message">Error al cargar los jugadores.</div>';
        });
});